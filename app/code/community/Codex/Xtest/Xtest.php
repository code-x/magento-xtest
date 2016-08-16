<?php

class Xtest
{
    protected static $args = null;

    public static function initAdmin()
    {
        self::init('admin');

        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            Mage_Core_Model_App_Area::AREA_ADMIN,
            true
        );

        Mage::app()->loadArea(Mage_Core_Model_App_Area::AREA_ADMINHTML);
    }

    public static function initFrontend($code = null)
    {
        if ($code === null) {
            $code = self::getArg('store_code', '');
        }
        self::init($code);

        Mage::register('isSecureArea', true, true);

        if (Mage::getStoreConfig('xtext/force/index')) {
            Mage::register('custom_entry_point', true, true);
        }

        Mage::app()->loadArea(Mage_Core_Model_App_Area::AREA_FRONTEND);

        Mage::getSingleton('core/translate')->setLocale(Mage::app()->getLocale()->getLocaleCode())->init(
            Mage_Core_Model_App_Area::AREA_FRONTEND,
            true
        );
    }

    protected static function init($code)
    {
        Mage::$headersSentThrowsException = false;

        $options = array();
        $options['config_model'] = 'Codex_Xtest_Model_Core_Config';
        $options['cache_dir'] = Mage::getBaseDir('var') . DS . 'cache' . DS . 'xtest';

        Mage::reset();
        Mage::app($code, 'store', $options);

        if ($disableDouble = (bool)self::getArg('disable_double', false)) {
            self::getConfig()->setDisableDoubles($disableDouble);
        }
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

    /**
     * @return $this
     */
    protected function _parseArgs()
    {
        self::$args = array();
        $current = null;
        foreach ($_SERVER['argv'] as $arg) {
            $match = array();
            if (preg_match('#^--([\w\d_-]{1,})$#', $arg, $match) || preg_match('#^-([\w\d_]{1,})$#', $arg, $match)) {
                $current = $match[1];
                self::$args[$current] = true;
            } else {
                if ($current) {
                    self::$args[$current] = $arg;
                } else {
                    if (preg_match('#^([\w\d_]{1,})$#', $arg, $match)) {
                        self::$args[$match[1]] = true;
                    }
                }
            }
        }

        return $this;
    }

    public static function getArg($name, $default = false)
    {
        if (self::$args === null) {
            self::_parseArgs();
        }

        if (isset(self::$args[$name])) {
            return self::$args[$name];
        }

        return $default;
    }
}