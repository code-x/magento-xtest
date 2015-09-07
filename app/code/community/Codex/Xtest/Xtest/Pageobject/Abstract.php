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
    protected function _takeScreenshot( $title = null )
    {
        if( !$title ) {
            $title = $this->title();
        }

        $caps = $this->getDesiredCapabilities();
        $title .= ' using '.$this->getBrowser()." ".$caps['version'];
        $this->getTestCase()->addScreenshot( $title, $this->currentScreenshot() );

        return $this;
    }

    public function takeScreenshot($title=null)
    {
        $this->resizeBrowserWindow(1280,1024);
        $this->_takeScreenshot($title);
    }

    /**
     * @param null $title
     */
    public function takeResponsiveScreenshots( $title = null )
    {
        if( !$title ) {
            $title = $this->title();
        }

        $sizes = Xtest::getArg('breakpoints', $this->getSeleniumConfig('screenshot/breakpoints') );

        foreach( explode(',', $sizes) AS $size )
        {
            list($w,$h) = explode('x', $size);
            $this->resizeBrowserWindow((int)$w,(int)$h);
            $this->_takeScreenshot( $title.' w'.$w );
        }
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

    /**
     * @param $text
     * @param bool $exactOnly
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     * @throws Exception
     */
    public function byLinkText( $text, $exactOnly = false )
    {
        try {
            return parent::byLinkText($text);
        } catch ( Exception $e ) {

            try {
                return parent::byLinkText(strtoupper($text));

            } catch ( Exception $e ) {

                $aTags = $this->findElementsByCssSelector('a');
                foreach( $aTags AS $aTag)
                {
                    $aText = $aTag->text();

                    if( strtolower( $aText ) ==  strtolower($text) )
                    {
                        return $aTag;
                    }

                    if( stripos( $aText, $text ) !== false ) {
                        return $aTag;
                    }

                }

            }
            throw $e;
        }
    }

    public function waitForAjax()
    {

        $this->waitUntil(function ( ) {
                try {
                    $activeConnections = 0;
                    $activeConnections += (int)$this->execute(
                        array(
                            'script' => 'return ( jQuery ? jQuery.active : 0 )',
                            'args'   => array()
                        )
                    );

                    $activeConnections += (int)$this->execute(
                        array(
                            'script' => 'return ( Ajax ? Ajax.activeRequestCount : 0)',
                            'args'   => array()
                        )
                    );

                    if( !is_numeric($activeConnections) || $activeConnections == 0 )
                    {
                        return true;
                    }

                    return null;

                } catch( Exception $e ) {
                    return true;
                }
                return null;
            }, 60000);

        sleep(0.5); // Rendering Time
    }


}