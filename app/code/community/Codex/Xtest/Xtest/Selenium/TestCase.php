<?php

class Codex_Xtest_Xtest_Selenium_TestCase extends PHPUnit_Extensions_Selenium2TestCase
{
    protected $_screenshots = array();

    /**
     * @param $modelClass
     * @return Codex_Xtest_Xtest_Pageobject_Abstract
     */
    public function getPageObject($modelClass)
    {
        /** @var $model Codex_Xtest_Xtest_Pageobject_Abstract */
        $model = Xtest::getXtest($modelClass);

        $model->setBrowser($this->getBrowser());
        $model->setBrowserUrl(Mage::getBaseUrl());

        $model->shareSession(true);
        $model->prepareSession();
        $model->setTestcase($this);

        return $model;
    }

    protected function setUp()
    {
        parent::setUp();
        $this->_screenshots = array();
        $this->setBrowser('firefox'); // TODO
        $this->setBrowserUrl(Mage::getBaseUrl());
        $this->shareSession(true);

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

    public function addModelDouble($modelClass, $doubleClass)
    {
        // TODO: Add Frontend Model Mocks
        $this->markTestIncomplete();
    }

}