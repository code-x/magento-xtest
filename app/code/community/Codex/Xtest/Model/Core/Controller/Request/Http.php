<?php

class Codex_Xtest_Model_Core_Controller_Request_Http extends Mage_Core_Controller_Request_Http
{

    protected $_method = null;

    public function setMethod( $method )
    {
        $this->_method = $method;
        return $this;
    }

    public function getMethod()
    {
        if( $this->_method ) {
            return $this->_method;
        }
        return parent::getMethod();
    }


}