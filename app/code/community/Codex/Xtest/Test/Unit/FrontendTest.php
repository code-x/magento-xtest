<?php

class Codex_Xtest_Test_Unit_FrontendTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    public function testDispatch()
    {
        $this->dispatch('customer/account/login');

        $this->assertContains('Login or Create an Account', $this->getResponseBody() );
        $this->assertInstanceOf('Mage_Page_Block_Html', $this->getLayout()->getBlock('root') );
    }

    public function testDispatchUrl()
    {
        $this->dispatchUrl( Mage::getUrl('customer/account/login') );

        $this->assertContains('Login or Create an Account', $this->getResponseBody() );
        $this->assertInstanceOf('Mage_Page_Block_Html', $this->getLayout()->getBlock('root') );
    }

}