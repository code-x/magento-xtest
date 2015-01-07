<?php

class Codex_Xtest_Xtest_Unit_Frontend extends Codex_Xtest_Xtest_Unit_Abstract
{
    protected function setUp()
    {
        parent::setUp();
        Xtest::initFrontend();
    }

    /**
     * Check if payment methdos are available
     *
     * @param Mage_Sales_Model_Quote $quote
     * @param array $allowedMethods
     */
    public function assertPaymentMethodsAvailable( Mage_Sales_Model_Quote $quote, Array $allowedMethods )
    {
        $availableMethods = array();
        $allActivePaymentMethods = Mage::getModel('payment/config')->getAllMethods();
        foreach( $allActivePaymentMethods AS $paymentMethod )
        {
            /** @var $paymentMethod Mage_Payment_Model_Method_Abstract */
            if( $paymentMethod->isAvailable( $quote ) ) {
                $availableMethods[] = $paymentMethod->getCode();
            }
        }
        sort($allowedMethods);
        sort($availableMethods);
        $this->assertEquals($allowedMethods, $availableMethods);
    }

    /**
     * Makes Quote available for Magento Core classes
     *
     * @param Mage_Sales_Model_Quote $quote
     */
    public function populuateQuote( Mage_Sales_Model_Quote &$quote )
    {
        $quote->save();
        $quote = Mage::getModel('sales/quote')->load( $quote->getId() );
        Mage::getSingleton('checkout/cart')->setQuote( $quote );
        Mage::getSingleton('checkout/session')->setQuoteId( $quote->getId() );
    }

}