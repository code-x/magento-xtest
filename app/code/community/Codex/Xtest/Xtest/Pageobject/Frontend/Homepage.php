<?php

class Codex_Xtest_Xtest_Pageobject_Frontend_Homepage extends Codex_Xtest_Xtest_Pageobject_Abstract
{
    public function open()
    {
        $this->url(Mage::getBaseUrl());
    }
}