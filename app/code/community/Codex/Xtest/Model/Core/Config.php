<?php

class Codex_Xtest_Model_Core_Config extends Mage_Core_Model_Config
{
    protected $modelMocks = array();
    protected $helperMocks = array();

    public function addModelMock($modelClass, $mockClassObj)
    {
        $key = '_singleton/' . $modelClass;
        if (Mage::registry($key)) {
            Mage::unregister($key);
        }

        $this->modelMocks[$modelClass] = $mockClassObj;
    }

    public function addHelperMock($helperName, $mockClassObj)
    {
        $this->registerHelper($helperName, $mockClassObj);

        // For helpers without /data
        if (strpos($helperName, '/') === false) {
            $this->registerHelper($helperName . '/data', $mockClassObj);
        }

        // Remember helperMock for
        $this->helperMocks[] = $helperName;
    }

    public function resetMocks()
    {
        $this->modelMocks = array();

        // Reset registry
        foreach ($this->helperMocks as $helperName) {
            $this->unregisterHelper($helperName);

            // For helpers without /data
            if (strpos($helperName, '/') === false) {
                $this->unregisterHelper($helperName . '/data');
            }
        }

        $this->helperMocks = array();
    }

    public function getModelInstance($modelClass = '', $constructArguments = array())
    {
        if ($classObj = $this->modelMocks[$modelClass]) {
            return clone $classObj;
        }

        $modelName = $this->getModelClassName($modelClass);
        $mockClassName = str_replace('_Model_', '_Test_Double_Model_', $modelName);
        if (class_exists($mockClassName)) {
            $obj = new $mockClassName($constructArguments);
            return $obj;
        }

        if( $modelName == 'Mage_Core_Model_Resource' )
        {
            $obj = new Codex_Xtest_Model_Core_Resource($constructArguments);
            return $obj;
        }

        return parent::getModelInstance($modelClass, $constructArguments);
    }

    public function getXtestClassName($modelClass)
    {
        $modelClass = trim($modelClass);
        if (strpos($modelClass, '/') === false) {
            return $modelClass;
        }
        return $this->getGroupedClassName('xtest', $modelClass);
    }

    public function getXtestInstance($modelClass = '', $constructArguments = array())
    {
        $className = $this->getXtestClassName($modelClass);
        if (class_exists($className)) {
            Varien_Profiler::start('CORE::create_object_of::' . $className);
            $obj = new $className($constructArguments);
            Varien_Profiler::stop('CORE::create_object_of::' . $className);
            return $obj;
        } else {
            return false;
        }
    }

    public function loadModules()
    {
        $res = parent::loadModules();
        $this->loadModulesConfiguration('xtest.xml', $this);
        return $res;
    }

    protected function registerHelper($helperName, $mockClassObj)
    {
        $registryKey = '_helper/' . $helperName;
        $this->unregisterHelper($helperName);
        Mage::register($registryKey, $mockClassObj);
    }

    protected function unregisterHelper($helperName)
    {
        $registryKey = '_helper/' . $helperName;
        if (Mage::registry($registryKey)) {
            Mage::unregister($registryKey);
        }
    }
}