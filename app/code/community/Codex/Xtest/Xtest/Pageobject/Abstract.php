<?php

class Codex_Xtest_Xtest_Pageobject_Abstract extends PHPUnit_Extensions_Selenium2TestCase
{

    protected $testCase;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @param $params
     */
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

    /**
     * @param Codex_Xtest_Xtest_Selenium_TestCase $case
     * @return $this
     */
    public function setTestCase( Codex_Xtest_Xtest_Selenium_TestCase $case )
    {
        $this->testCase = $case;
        return $this;
    }

    /**
     * @param null $title
     * @return $this
     */
    public function takeScreenshot( $title = null )
    {
        if( !$title ) {
            $title = $this->title();
        }

        $title .= ' using '.$this->getBrowser();
        $this->getTestCase()->addScreenshot( $title, $this->currentScreenshot() );

        return $this;
    }

    /**
     * @param null $title
     */
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

    /**
     * @param int $width
     * @param int $height
     */
    public function resizeBrowserWindow($width = 1280, $height = 1024) {
        $this->prepareSession()->currentWindow()->size(array('width' => $width, 'height' => $height));
    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @param string $msg
     */
    public function assertElementIsVisible( \PHPUnit_Extensions_Selenium2TestCase_Element $element, $msg = 'Element is not visible, but should be'  )
    {
        $this->assertTrue( $element->displayed(), $msg );

    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @param string $msg
     */
    public function assertElementIsNotVisible( \PHPUnit_Extensions_Selenium2TestCase_Element $element, $msg = "Element is not visible, but should be" )
    {
        $this->assertFalse( $element->displayed(), $msg );
    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @param string $msg
     */
    public function assertElementIsVisibleInViewport( \PHPUnit_Extensions_Selenium2TestCase_Element $element, $msg = "Element is not visible in viewport, but should be" )
    {
        $this->assertTrue( $this->isVisibleInViewport( $element ) );
    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @param string $msg
     */
    public function assertElementIsNotVisibleInViewport( \PHPUnit_Extensions_Selenium2TestCase_Element $element, $msg = "Element is visible in viewport, but should not" )
    {
        $this->assertFalse( $this->isVisibleInViewport( $element ) );
    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @return bool
     */
    public function isVisibleInViewport( \PHPUnit_Extensions_Selenium2TestCase_Element $element )
    {
        if( !$element->displayed() ) {
            return false;
        }
        $this->markTestIncomplete('not implemented'); // TODO: Noch berechnen!
        return true;
    }

    /**
     * @param $selector
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $root_element
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function findElementsByCssSelector( $selector, \PHPUnit_Extensions_Selenium2TestCase_Element $root_element = null )
    {
        if( !$root_element )
        {
            $root_element = $this;
        }
        return $root_element->elements( $this->using('css selector')->value( $selector ) );
    }

    /**
     * @param $class
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function assertElementHasClass( $class, \PHPUnit_Extensions_Selenium2TestCase_Element $element )
    {
        $classes = explode(' ', $element->attribute('class') );
        $this->assertContains($class, $classes);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getSeleniumConfig($path)
    {
        return $this->getTestCase()->getSeleniumConfig($path);
    }


}