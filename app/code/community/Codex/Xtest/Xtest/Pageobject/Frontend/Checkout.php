<?php

class Codex_Xtest_Xtest_Pageobject_Frontend_Checkout extends Codex_Xtest_Xtest_Pageobject_Abstract
{

    public function open()
    {
        $this->url( Mage::getUrl('checkout/onepage') );
    }

    public function getLoginForm()
    {
        return $this->byId('login-form');
    }

    public function login( $email, $password )
    {
        $this->getLoginForm()->byId('login-email')->value($email);
        $this->getLoginForm()->byId('login-password')->value($password);
        $this->getLoginForm()->submit();
    }

    /**
     * @param $step_id eg billing, shipping,
     */
    public function assertStepIsActive( $step_id )
    {
        $step = $this->byId('opc-'.$step_id);
        $this->assertElementHasClass('active', $step );
    }

    public function getActiveStepName()
    {
        $active = $this->byCssSelector('#checkoutSteps .section.active');
        return substr($active->attribute('id'), 4);
    }

    public function setBillingAddress( $data = null )
    {
        if( $data === null )
        {
            $data = $this->getSeleniumConfig('checkout/billing_address');
        }

        foreach( $data AS $key => $value )
        {
            if( $element = $this->byId('billing:'.$key) ) {
                $element->value( $value );
            }
        }

    }

    public function nextStep()
    {
        $activeStepName = $this->getActiveStepName();

        $activeStepElement = $this->byId('opc-'.$activeStepName);

        $activeStepElement->byCssSelector('.buttons-set button')->click();

        $this->waitUntil(function ( ) use ( $activeStepName ) {
            if ( $this->getActiveStepName() != $activeStepName ) {
                return true;
            }
            return null;
        }, 5000);
    }

}

