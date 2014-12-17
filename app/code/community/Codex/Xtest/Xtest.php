<?php

class Xtest
{

    public static function initAdmin($withCustomConfig = false)
    {
        self::init('admin', $withCustomConfig);

        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            'admin',
            true
        );
    }

    public static function initFrontend($code = null, $withCustomConfig = false)
    {
        if ($code === null) {
            $code = '';
        }
        self::init($code, $withCustomConfig);


        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            'frontend',
            true
        );
    }

    protected static function init($code, $withCustomConfig = false)
    {
        $options = array();
        if ($withCustomConfig) {
            $options['config_model'] = 'Codex_Xtest_Model_Core_Config';
        }

        Mage::reset();
        Mage::app($code, 'store', $options);
    }

    /**
     * @return Codex_Xtest_Model_Core_Config
     */
    public static function getConfig()
    {
        return Mage::getConfig();
    }

    public static function getXtest($modelClass = '', $arguments = array())
    {
        return self::getConfig()->getXtestInstance($modelClass, $arguments);
    }

}