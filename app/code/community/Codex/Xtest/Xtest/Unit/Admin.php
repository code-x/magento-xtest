<?php

class Codex_Xtest_Xtest_Unit_Admin extends Codex_Xtest_Xtest_Unit_Abstract
{
    protected function setUp()
    {
        parent::setUp();
        Xtest::initAdmin();
    }
}