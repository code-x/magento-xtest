<?php

class Codex_Xtest_Xtest_Fixture_Customer extends Codex_Xtest_Xtest_Fixture_Abstract
{

    protected $_password;
    protected $_email;

    /**
     * @param bool $cleanup delete customer first
     */
    public function getTest($cleanup = true)
    {
        $customerConfig = $this->getConfigFixture('customer');
        $this->_email = $customerConfig['email'];

        if ($cleanup) {
            // Testkunde löschen, dann neuen anlegen
            $customerCol = Mage::getModel('customer/customer')->getCollection();
            $customerCol->addFieldToFilter('email', $this->getEmail());
            $customerCol->walk('delete');
        }

        // Neuen Testkunden erstellen
        $customer = Mage::getModel('customer/customer');
        $customer->setData($customerConfig);
        $this->_password = $customer->generatePassword();
        $customer->setStore(current(Mage::app()->getStores())); // TODO: Warum ist das nicht automatisch der richtige?
        $customer->setPassword($this->getPassword());
        $customer->validate();
        $customer->save();
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