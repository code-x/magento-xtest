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

        Mage::register('isSecureArea', true, true);
        Mage::app()->loadArea( Mage_Core_Model_App_Area::AREA_FRONTEND );

        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            'frontend',
            true
        );
    }

    protected static function init($code)
    {
        $options = array();
        $options['config_model'] = 'Codex_Xtest_Model_Core_Config';
        $options['cache_dir'] = Mage::getBaseDir('var').DS.'cache'.DS.'xtest';

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

    /**
     * @param $model string model syntax or class name (codex_module/observer or Codex_Module_Model_Observer)
     * @param $method string the exact method name (as defined in confix.xml)
     * @param $eventName string the original name of the event
     * @param array $args array
     */
    public static function dispatchEvent($model, $method, $eventName, array $args = array())
    {
        $event = new Varien_Event($args);
        $event->setName($eventName);

        $observer = new Varien_Event_Observer();
        $observer->setData(array('event' => $event));
        $observer->addData($args);

        $object = Mage::getModel($model);
        if (method_exists($object, $method)) {
            $object->$method($observer);
        }
    }
}