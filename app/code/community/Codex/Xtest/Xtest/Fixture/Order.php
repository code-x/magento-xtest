<?php

class Codex_Xtest_Xtest_Fixture_Order extends Codex_Xtest_Xtest_Fixture_Abstract
{
    protected $quote;

    /**
     * @return Codex_Xtest_Xtest_Fixture_Quote
     */
    public function getFixtureQuote()
    {
        if(!$this->quote) {
            /** @var $quote */
            $this->quote = Xtest::getXtest('xtest/fixture_quote');
        }
        return $this->quote;
    }

    public function reset()
    {
        $this->quote = null;
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getTest()
    {
        return $this->convertQuoteToOrder($this->getFixtureQuote()->getTest());
    }

    /**
     * @param $quote
     * @return Mage_Sales_Model_Order
     */
    public function convertQuoteToOrder( $quote )
    {
        /* @var $service Mage_Sales_Model_Service_Quote */
        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();

        return $service->getOrder();
    }

}