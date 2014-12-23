<?php

class Codex_Xtest_Test_Integration_Mocking extends Codex_Xtest_Xtest_Unit_Frontend
{

    public function testModelMock()
    {
        $testReturn = 'Test-Name';

        $mock = $this->getModelMock('catalog/product', array('getName') );
        $mock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue( $testReturn ));

        $this->assertEquals( $testReturn, $mock->getName() );

        $this->addModelMock('catalog/product', $mock );

        $product = Mage::getModel('catalog/product');
        $this->assertInstanceOf( get_class($mock), $product );
        $this->assertEquals( $product, $mock );
    }

    public function testHelperMock()
    {
        $this->markTestIncomplete();
    }

    public function testSingletonMock()
    {
        $this->markTestIncomplete();
    }

    public function testBlockMock()
    {
        $this->markTestIncomplete();
    }

}