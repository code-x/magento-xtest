<?php

class Codex_Xtest_Xtest_Fixture_Customer extends Codex_Xtest_Xtest_Fixture_Abstract
{
    protected $_password;
    protected $_email;

    /**
     * Get Random Testcustomer
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getTestRandom()
    {
        $this->setConfigFixture('customer/email', uniqid() . '@test.code-x.de');
        return $this->getTest();
    }

    /**
     * @param bool $cleanup
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getTest($cleanup = true)
    {
        /** @var $store Mage_Core_Model_Store */
        $store = current(Mage::app()->getStores());

        /** @var Mage_Customer_Model_Customer $customer */
        $customer = Mage::getModel('customer/customer');

        $customerConfig = $this->getConfigFixture('customer');
        $this->_email = $customerConfig['email'];

        $this->_password = $customer->generatePassword();

        if ($cleanup) {
            // Testkunde löschen, dann neuen anlegen

            $customerCol = Mage::getModel('customer/customer')->getCollection();
            $customerCol->addFieldToFilter('email', $this->getEmail());

            foreach ($customerCol AS $tmpCustomer) {
                $orderCol = Mage::getModel('sales/order')->getCollection();
                $orderCol->addFieldToFilter('customer_id', $tmpCustomer->getId());

                $tmpCustomer->delete();
            }
        } else {
            $customer->loadByEmail($this->_email);
        }

        // Neuen Testkunden erstellen
        $customer->setData($customerConfig);
        $customer->setStore($store);

        $customer->setPassword($this->getPassword());
        $customer->validate();
        $customer->save();

        $address = Mage::getModel('customer/address');
        $address->addData($customer['billing_address']);
        $address->setIsDefaultBilling('1');
        $address->setCustomerId($customer->getId());
        $address->save();
        $customer->addAddress($address);

        $address = Mage::getModel('customer/address');
        $address->addData($customer['shipping_address']);
        $address->setIsDefaultShipping('1');
        $address->setCustomerId($customer->getId());
        $address->save();
        $customer->addAddress($address);

        return $customer;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }
}