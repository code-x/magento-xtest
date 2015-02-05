<?php

class Codex_Xtest_Model_Varien_Db_Adapter_Pdo_Mysql extends Varien_Db_Adapter_Pdo_Mysql
{

    public function commit()
    {
        return $this;
    }

    public function rollBack()
    {
        return $this;
    }

    public function beginTransaction()
    {
        return $this;
    }


}