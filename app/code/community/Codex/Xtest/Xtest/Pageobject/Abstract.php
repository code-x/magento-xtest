<?php

class Codex_Xtest_Xtest_Pageobject_Abstract extends PHPUnit_Extensions_Selenium2TestCase
{

    protected $testCase;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    protected function setUpSessionStrategy($params)
    {
        self::$browserSessionStrategy = new Codex_Xtest_Model_Phpunit_Session_Pageobject();
       $this->localSessionStrategy = self::$browserSessionStrategy;
    }

    /**
     * @return Codex_Xtest_Xtest_Selenium_TestCase
     */
    public function getTestCase()
    {
        return $this->testCase;
    }

    public function setTestCase( Codex_Xtest_Xtest_Selenium_TestCase $case )
    {
        $this->testCase = $case;
        return $this;
    }

    public function takeScreenshot( $title = null )
    {
        if( !$title ) {
            $title = $this->title();
        }
        $title .= ' using '.$this->getBrowser();
        $this->getTestCase()->addScreenshot( $title, $this->currentScreenshot() );

        return $this;
    }

    public function takeResponsiveScreenshots( $title = null )
    {
        if( !$title ) {
            $title = $this->title();
        }

        $this->resizeBrowserWindow(450,1024);
        $this->takeScreenshot( $title.' w450' );

        $this->resizeBrowserWindow(1280,1024);
        $this->takeScreenshot( $title.' w1280' );

    }

    public function resizeBrowserWindow($width = 1280, $height = 1024) {
        $this->prepareSession()->currentWindow()->size(array('width' => $width, 'height' => $height));
    }

    public function assertElementIsVisible( $cssSelector )
    {
        $this->assertTrue( $this->byCssSelector( $cssSelector )->displayed(), "$cssSelector is not visible" );
    }

    public function assertElementIsNotVisible( $cssSelector )
    {
        $this->assertFalse( $this->byCssSelector( $cssSelector )->displayed(), "$cssSelector is not visible" );
    }

}