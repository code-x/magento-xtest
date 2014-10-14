<?php

class Codex_Xtest_Model_Core_Config extends Mage_Core_Model_Config
{
    protected $modelMocks = array();

    public function addModelMock($modelClass, $mockClassObj)
    {
        $this->modelMocks[$modelClass] = $mockClassObj;
    }

    public function resetMocks()
    {
        $this->modelMocks = array();
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

}