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
        Mage::getSingleton('checkout/session')->replaceQuote( $quote );
        Mage::getSingleton('checkout/type_onepage')->setQuote( $quote );
    }

    public function setCustomerAsLoggedIn($customer = null)
    {
        if( $customer === null )
        {
            /** @var $customerFixture Codex_Xtest_Xtest_Fixture_Customer */
            $customerFixture = Xtest::getXtest('xtest/fixture_customer');
            $customer = $customerFixture->getTest();
            $customer->setConfirmation(null);
            $customer->save();

            Mage::dispatchEvent('xtest_fixture_customer_create', array( 'customer' => $customer ) );
        }

        Mage::getSingleton('customer/session')->setCustomer($customer);
        Mage::dispatchEvent('customer_login', array('customer'=>$customer));
        return $this;
    }

}