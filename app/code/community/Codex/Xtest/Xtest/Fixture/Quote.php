<?php

class Codex_Xtest_Xtest_Fixture_Quote
{

    public function getTest()
    {
        /* @var $quote Mage_Sales_Model_Quote */
        $quote = Mage::getModel('sales/quote')
            ->setStoreId(Mage::app()->getStore('default')->getId());

        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(1)
            ->loadByEmail('magento@code-x.de');
        $quote->assignCustomer($customer);

        // add product
        $productId = 1;

        /* @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product');

        $buyInfo = array('qty' => 1);
        $quote->addProduct($product->load($productId), new Varien_Object($buyInfo));

        $addressData     = array(
            'firstname'  => 'Test',
            'lastname'   => 'Test',
            'street'     => 'Sample Street 10',
            'city'       => 'Somewhere',
            'postcode'   => '123456',
            'telephone'  => '123456',
            'country_id' => 'US',
            'region_id'  => 12, // id from directory_country_region table
        );
        $billingAddress  = $quote->getBillingAddress()->addData($addressData);
        $shippingAddress = $quote->getShippingAddress()->addData($addressData);

        $quote->getShippingAddress()
            ->setCollectShippingRates(true)
            ->setShippingMethod('flatrate_flatrate')
            ->setPaymentMethod('debit');

        $quote->getPayment()->importData(
            array(
                'debit_cc_owner' => 'Test',
                'debit_iban' => 'Test',
                'debit_swift' => 'Test',
                'method' => 'debit',
            )
        );
        $quote->collectTotals()->save();
        return $quote;
    }

}