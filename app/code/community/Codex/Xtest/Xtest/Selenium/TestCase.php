<?php

define('BROWSERSTACK_USER', 'tobias63');
define('BROWSERSTACK_KEY', 'i3495etfJwuPyuytYCwE');

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

        $browserName = Xtest::getArg('browser', 'firefox');
        $browserData = Mage::getConfig()->getNode('default/xtest/selenium/browserlist/'.strtolower($browserName) );

        if( $browserData ) {
            $browserData = $browserData->asArray();

            $capabilities = array();

            if( $browserData['is_browserstack'] )
            {
                if( $browserstackConfig = Mage::getConfig()->getNode('default/xtest/selenium/browserstack') )
                {
                    $browserstackConfig = $browserstackConfig->asArray();

                    $this->setHost( $browserstackConfig['host'] );
                    $this->setPort( (int)$browserstackConfig['port'] );

                    if( file_exists($browserstackConfig['authfile']) )
                    {
                        list($user,$key) = explode(':', file_get_contents($browserstackConfig['authfile']));
                        $capabilities['browserstack.user'] = trim($user);
                        $capabilities['browserstack.key'] = trim($key);
                    }
                }
            }
            $this->setBrowser( $browserData['name'] );

            if( $caps = $browserData['capabilities'] ) {
                $capabilities = array_merge( $capabilities, $caps );
            }

            $this->setDesiredCapabilities( $capabilities );

        } else {
            $this->setBrowser( $browserName );
        }
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

            if( Xtest::getArg('debug') )
            {

                echo PHP_EOL."got '".$e->getMessage()."' exception. press any key to continue..".PHP_EOL;
                ob_end_flush();

                fgets(STDIN);
            }
            throw $e;
        }
    }
}