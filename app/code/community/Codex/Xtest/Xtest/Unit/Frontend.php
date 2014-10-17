<?php

class Codex_Xtest_Xtest_Unit_Frontend extends Codex_Xtest_Xtest_Unit_Abstract
{
    protected function setUp()
    {
        parent::setUp();
        Xtest::initFrontend();
    }

    public function assertPaymentMethodsAvailable( Mage_Sales_Model_Quote $quote, Array $allowedMethods )
    {
        $availableMethods = array();
        $allActivePaymentMethods = Mage::getModel('payment/config')->getActiveMethods();
        foreach( $allActivePaymentMethods AS $paymentMethod )
        {
            /** @var $paymentMethod Mage_Payment_Model_Method_Abstract */
            if( $paymentMethod->isAvailable( $quote ) ) {
                $availableMethods[] = $paymentMethod->getCode();
            }
        }
        sort($allowedMethods);
        sort($availableMethods);
        $this->assertEquals($allowedMethods, $availableMethods );
    }

}