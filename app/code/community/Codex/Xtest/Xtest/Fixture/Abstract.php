<?php

abstract class Codex_Xtest_Xtest_Fixture_Abstract
{



    public function getConfigFixture($path)
    {
        $config = Mage::getStoreConfig('xtest/fixtures/'.$path);
        if( $config === NULL ) {
            Mage::throwException( sprintf('Config path %s is null', $path) );
        }
        return $config;
    }

    public function setConfigToObject( Varien_Object $object, $rootPath )
    {
        $dataSets = Xtest::get('xtest/'.$rootPath);



    }
}