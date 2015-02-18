<?php

class Codex_Xtest_Xtest_Selenium_TestCase extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $_screenshots = array();

    protected $_resetSession = true;

    /**
     * @param $modelClass
     * @return Codex_Xtest_Xtest_Pageobject_Abstract
     */
    public function getPageObject($modelClass)
    {
        /** @var $model Codex_Xtest_Xtest_Pageobject_Abstract */
        $model = Xtest::getXtest($modelClass);
        $model->setTestcase($this);

        $model->setUpSessionStrategy(null);
        $model->prepareSession();

        $model->setBrowser($this->getBrowser());
        $model->setBrowserUrl(Mage::getBaseUrl());

        return $model;
    }

    protected function setUp()
    {
        parent::setUp();

        $this->_screenshots = array();
        $this->setBrowser('firefox'); // TODO
        $this->setBrowserUrl(Mage::getBaseUrl());

        $this->setUpSessionStrategy(null);

        // Default Browser-Size
        $this->prepareSession()->currentWindow()->size(array('width' => 1280, 'height' => 1024));

        Xtest::initFrontend();
    }

    public function getScreenshots()
    {
        return $this->_screenshots;
    }

    public function addScreenshot($name, $content)
    {
        $this->_screenshots[] = array($name, $content);
        return $this;
    }

    protected function setUpSessionStrategy($params)
    {
        self::$browserSessionStrategy = new Codex_Xtest_Model_Phpunit_Session_Pageobject();

        if( $this->_resetSession ) {
            self::$browserSessionStrategy->reset();
        }

        $this->localSessionStrategy = self::$browserSessionStrategy;
    }

    public function addModelDouble($modelClass, $doubleClass)
    {
        // TODO: Add Frontend Model Mocks
        $this->markTestIncomplete();
    }

    public static function getSeleniumConfig($path)
    {
        $path = 'xtest/selenium/'.$path;
        $config = Mage::getStoreConfig($path);
        if( $config === NULL ) {
            Mage::throwException( sprintf('Config path %s is null', $path) );
        }
        return $config;
    }


    public function enableSessionSharing()
    {
        $this->_resetSession = false;
    }

    public static function tearDownAfterClass()
    {
        if( self::$browserSessionStrategy) {
            self::$browserSessionStrategy->reset();
        }
        parent::tearDownAfterClass();
    }



    protected function runTest()
    {
        try {
            return parent::runTest();
        } catch( Exception $e )
        {

            if( in_array('--debug', $_SERVER['argv'] ) )
            {

                echo PHP_EOL."got '".$e->getMessage()."' exception. press any key to continue..".PHP_EOL;
                ob_end_flush();

                fgets(STDIN);
                throw $e;


            }
        }
    }
}