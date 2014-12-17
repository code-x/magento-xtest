<?php

abstract class Codex_Xtest_Xtest_Fixture_Abstract
{

    protected $_config = array();

    public function getConfigFixture($path)
    {
        if( $this->_config[ $path ] !== null ) {
            return $this->_config[ $path ];
        }

        $path = 'xtest/fixtures/'.$path;

        $config = Mage::getStoreConfig($path);
        if( $config === NULL ) {
            Mage::throwException( sprintf('Config path %s is null', $path) );
        }
        return $config;
    }

    public function setConfigFixture($path, $value)
    {
        $this->_config[ $path ] = $value;
        return $this;
    }

}