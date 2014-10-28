<?php

class Codex_Xtest_Xtest_Pageobject_Frontend_Customer extends Codex_Xtest_Xtest_Pageobject_Abstract
{

    public function open()
    {
        $url = Mage::getUrl('customer/account');
        if ( $url != $this->url() ) {
            $this->url($url);
        }
    }

    public function login($user, $password)
    {
        $this->open();

        $this->getLoginEmailInput()->value($user);
        $this->getLoginPasswordInput()->value($password);
        $this->getLoginButton()->click();
    }

    public function getLoginForm()
    {
        return $this->byId('login-form');
    }

    public function getLoginEmailInput()
    {
        return $this->getLoginForm()->byId('email');
    }

    public function getLoginPasswordInput()
    {
        return $this->getLoginForm()->byId('pass');
    }

    public function getLoginButton()
    {
        return $this->getLoginForm()->byId('send2');
    }

    public function getSuccessMessage()
    {
        return $this->byCssSelector('li.success-msg')->text();
    }



}