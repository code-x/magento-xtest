<?php

class Xtest
{

    public static function initAdmin()
    {
        self::init('admin');

        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            'admin',
            true
        );

        Mage::app()->loadArea( Mage_Core_Model_App_Area::AREA_ADMINHTML);
    }

    public static function initFrontend($code = null)
    {
        if ($code === null) {
            $code = '';
        }
        self::init($code);

        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            'frontend',
            true
        );

        Mage::register('isSecureArea', true, true);
        Mage::app()->loadArea( Mage_Core_Model_App_Area::AREA_FRONTEND );
    }

    protected static function init($code)
    {
        $options = array();
        $options['config_model'] = 'Codex_Xtest_Model_Core_Config';

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