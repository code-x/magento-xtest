<?php

class Codex_Xtest_Xtest_Unit_Frontend extends Codex_Xtest_Xtest_Unit_Abstract
{
    protected function setUp()
    {
        parent::setUp();
        Xtest::initFrontend(null, true );
    }
}