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

    public function setShippingAddress( $data = null )
    {
        if( $this->byId('shipping-address-select') ) {
            $this->select( $this->byId('shipping-address-select') )->selectOptionByValue("");
        }

        if( $data === null )
        {
            $data = $this->getSeleniumConfig('checkout/shipping_address');
        }

        foreach ($data AS $key => $value) {
            try {
                if ($element = $this->byId('shipping:' . $key)) {
                    if ($element->displayed()) {
                        $element->value($value);
                    }
                }
            } catch (Exception $e) {

            }
        }

    }

    public function setBillingAddress( $data = null )
    {
        if( $this->byId('billing-address-select') ) {
            $this->select( $this->byId('billing-address-select') )->selectOptionByValue("");
        }

        if( $data === null )
        {
            $data = $this->getSeleniumConfig('checkout/billing_address');
        }

        foreach( $data AS $key => $value )
        {
            if( $element = $this->byId('billing:'.$key) ) {
                if( $element->displayed() ) {
                    $element->value( $value );
                }
            }
        }

    }

    public function setShippingMethod( $data = null )
    {
        if( $data === null )
        {
            $data = $this->getSeleniumConfig('checkout/shipping_method');
        }

        $this->getActiveStepElement()->byId('s_method_'.$data['method'])->click();
    }

    public function setPaymentMethod( $data = null )
    {
        if( $data === null )
        {
            $data = $this->getSeleniumConfig('checkout/payment_method');
        }

        try {
            $this->getActiveStepElement()->byId('p_method_'.$data['method'])->click();
        } catch( Exception $e )
        {
            // TODO: Wenn es nur eine gibt vorher logisch abfangen
        }

        unset( $data['method'] );

        foreach( $data AS $key => $value )
        {
            try {
                if( $element = $this->byId($key) ) {
                    $element->value( $value );
                }
            } catch ( \PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e ) {
                // Do nothing here
            }
        }
    }

    public function acceptAgreements()
    {
        $activeStepElement = $this->getActiveStepElement();
        $checkboxes = $this->findElementsByCssSelector('input[type=checkbox]', $activeStepElement);
        foreach( $checkboxes AS $checkbox )
        {
            $checkbox->click();
        }
    }

    public function assertIsSuccessPage()
    {
        $this->assertContains('onepage/success', $this->url() );
    }

    public function getActiveStepElement()
    {
        return $this->byId('opc-'.$this->getActiveStepName() );
    }

    public function nextStep()
    {
        $activeStepName = $this->getActiveStepName();
        $currentLocation = (string)$this->url();

        $this->getActiveStepElement()->byCssSelector('.buttons-set button')->click();

        $this->waitUntil(function ( ) use ( $activeStepName, $currentLocation ) {
            try {
            if ( $this->getActiveStepName() != $activeStepName ||
                (string)$this->url() != $currentLocation ) {
                return true;
            }
            } catch( Exception $e ) {
                return true;
            }
            return null;
        }, 60000);

        sleep(0.5); // Rendering Time
    }

}

