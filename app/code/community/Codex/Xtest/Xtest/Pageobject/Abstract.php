<?php

class Codex_Xtest_Xtest_Pageobject_Abstract extends PHPUnit_Extensions_Selenium2TestCase
{

    protected $testCase;

    /**
     * @return Codex_Xtest_Model_Framework_Selenium_TestCase
     */
    public function getTestCase()
    {
        return $this->testCase;
    }

    public function setTestCase( Codex_Xtest_Model_Framework_Selenium_TestCase $case )
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

        $this->resizeBrowserWindow(1024,768);
        $this->getTestCase()->addScreenshot( $title, $this->currentScreenshot() );

        return $this;
    }

    public function resizeBrowserWindow($width = 1280, $height = 1024) {
        $this->prepareSession()->currentWindow()->size(array('width' => $width, 'height' => $height));
    }

}