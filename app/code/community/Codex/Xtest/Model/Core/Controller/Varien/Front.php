<?php

class Codex_Xtest_Model_Core_Controller_Varien_Front extends Mage_Core_Controller_Varien_Front
{
    public function dispatch()
    {
        $request = $this->getRequest();

        // If pre-configured, check equality of base URL and requested URL
        //$this->_checkBaseUrl($request);

        $request->setPathInfo()->setDispatched(false);

        $this->_getRequestRewriteController()->rewrite();

        Varien_Profiler::start('mage::dispatch::routers_match');
        $i = 0;

        while (!$request->isDispatched() && $i++ < 100) {
            foreach ($this->_routers as $router) {
                /** @var $router Mage_Core_Controller_Varien_Router_Abstract */
                if ($router->match($request)) {
                    break;
                }
            }
        }

        Varien_Profiler::stop('mage::dispatch::routers_match');

        if ($i > 100) {
            Mage::throwException('Front controller reached 100 router match iterations');
        }
    }

    public function setRouter($router)
    {
        $this->_routers = $router;
    }
}