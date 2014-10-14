<?php

class Codex_Xtest_Xtest_Fixture_Order
{

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getTest()
    {
        /** @var $quote Codex_Xtest_Xtest_Fixture_Quote */
        $quote = Xtest::getXtest('xtest/fixture_quote');
        return $this->convertQuoteToOrder($quote->getTest());
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