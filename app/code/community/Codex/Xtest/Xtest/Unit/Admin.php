<?php

class Codex_Xtest_Xtest_Unit_Admin extends Codex_Xtest_Xtest_Unit_Abstract
{
    protected function setUp()
    {
        parent::setUp();
        Xtest::initAdmin();
    }

    protected function _doDispatch(Codex_Xtest_Model_Core_Controller_Request_Http $request, $postData = null, $adminuser = null)
    {
        Mage::app()->getStore()->setConfig("admin/security/use_form_key", 0);

        if (!$adminuser) {
            $adminusers = Mage::getModel('admin/user')->getCollection();
            if ($adminusers->getSize() >= 1) {
                $adminuser = $adminusers->getFirstItem();
            }
        }
        $adminsession = Mage::getSingleton('admin/session');
        $adminsession->setUser($adminuser);
        $adminsession->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());

        parent::_doDispatch($request, $postData);
    }
}