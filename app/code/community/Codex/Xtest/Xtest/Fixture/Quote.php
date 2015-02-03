<?php

class Codex_Xtest_Xtest_Fixture_Quote extends Codex_Xtest_Xtest_Fixture_Abstract
{

    public function setCustomerEmail( $email )
    {
        $this->setConfigFixture('order/customer_id', '0');
        $this->setConfigFixture('order/customer_data/email', $email);
        return $this;
    }

    public function getTest( $customer = null )
    {
        /* @var $quote Mage_Sales_Model_Quote */
        $quote = Mage::getModel('sales/quote')->setStoreId(Mage::app()->getStore()->getId());

        if( $customer_id = $this->getConfigFixture('order/customer_id') )
        {
            $customer = Mage::getModel('customer/customer');
            $customer->load( (int)$customer_id );
            $quote->setCustomer( $customer );
        } else {
            foreach( $this->getConfigFixture('order/customer_data') AS $k => $v)
            {
                $quote->setData('customer_'.$k, $v);
            }
        }

        if( $customer )
        {
            $quote->setCustomer( $customer );
        }

        $items = $this->getConfigFixture('order/items');
        foreach( $items AS $item )
        {
            /* @var $product Mage_Catalog_Model_Product */
            $product = Mage::getModel('catalog/product');

            if( $item['product_id'] ) {
                $product->load( $item['product_id'] );
            } elseif( $item['sku'] ) {
                $product->load( $product->getIdBySku( $item['sku'] ) );
            } else {
                Mage::throwException('product not found');
            }

            $quote->addProduct($product, new Varien_Object($item));
        }

        $billingAddress  = $quote->getBillingAddress()->addData( $this->getConfigFixture('order/billing_address') );
        $shippingAddress = $quote->getShippingAddress()->addData( $this->getConfigFixture('order/shipping_address') );

        $quote->getShippingAddress()
            ->setCollectShippingRates(true)
            ->setShippingMethod( $this->getConfigFixture('order/shipping_method/method') )
            ->setPaymentMethod( $this->getConfigFixture('order/payment_method/method') );

        if( $importData = $this->getConfigFixture('order/payment_method') )
        {
            $quote->getPayment()->importData( $importData );
        }

        $quote->collectTotals()->save();

        return $quote;
    }

}