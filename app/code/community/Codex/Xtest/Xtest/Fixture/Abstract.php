<?php

abstract class Codex_Xtest_Xtest_Fixture_Abstract
{
    protected $_config = array();

    public function getConfigFixture($pPath)
    {
        if ($this->_config[$pPath] !== null) {
            return $this->_config[$pPath];
        }

        $path = 'xtest/fixtures/' . $pPath;

        $config = Mage::getStoreConfig($path);
        if ($config === null) {
            Mage::throwException(sprintf('Config path %s is null', $path));
        }

        if (is_array($config)) {
            $tmp = array();
            foreach ($config AS $k => $v) {
                $tmp[$k] = $this->getConfigFixture($pPath . '/' . $k);
            }
            return $tmp;
        }

        return $config;
    }

    public function setConfigFixture($path, $value)
    {
        $this->_config[$path] = $value;
        return $this;
    }
}