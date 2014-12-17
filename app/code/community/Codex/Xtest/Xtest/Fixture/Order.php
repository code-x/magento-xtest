<?php

class Codex_Xtest_Xtest_Fixture_Order
{

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getTest()
    {
        $quote = Mage::getModel('xtest/framework_fixture_quote');
        return $this->convertQuoteToOrder($quote);
    }

    /**
     * @param $quote
     * @return Mage_Sales_Model_Order
     */
    public function convertQuoteToOrder( $quote )
    {
        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();
        /* @var $service Mage_Sales_Model_Service_Quote */

        return $service->getOrder();
    }

}