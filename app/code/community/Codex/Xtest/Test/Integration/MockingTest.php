<?php

require_once __DIR__ . '/files/Test/Double/Catalog/Product.php';

class Codex_Xtest_Test_Integration_MockingTest extends Codex_Xtest_Xtest_Unit_Frontend
{
    public function testDoubleParameter()
    {
        $this->assertEquals('Mage_Catalog_Test_Double_Model_Product', get_class(Mage::getModel('catalog/product')));

        Xtest::getConfig()->setDisableDoubles(true);
        $this->assertEquals('Mage_Catalog_Model_Product', get_class(Mage::getModel('catalog/product')));
    }

    public function testModelMock()
    {
        $testReturn = 'Test-Name';

        $mock = $this->getModelMock('catalog/product', array('getName'));
        $mock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($testReturn));

        $this->assertEquals($testReturn, $mock->getName());

        $this->addModelMock('catalog/product', $mock);

        $product = Mage::getModel('catalog/product');
        $this->assertInstanceOf(get_class($mock), $product);
        $this->assertEquals($product, $mock);
    }

    public function testHelperMock()
    {
        $testReturn = 'http://www.webguys.de';

        $mock = $this->getHelperMock('catalog/product', array('getProductUrl'));
        $mock->expects($this->any())
            ->method('getProductUrl')
            ->will($this->returnValue($testReturn));

        $this->assertEquals($testReturn, $mock->getProductUrl());

        $this->addHelperMock('catalog/product', $mock);

        $product = Mage::helper('catalog/product');
        $this->assertInstanceOf(get_class($mock), $product);
        $this->assertEquals($product, $mock);
    }

    public function testSingletonMock()
    {
        $testReturn = 'Test-Name';

        $mock = $this->getModelMock('catalog/product', array('getName'));
        $mock->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($testReturn));

        $this->assertEquals($testReturn, $mock->getName());

        $this->addModelMock('catalog/product', $mock);

        $product = Mage::getSingleton('catalog/product');
        $this->assertInstanceOf(get_class($mock), $product);
        $this->assertEquals($product, $mock);
        $product->setId(1000);

        $product = Mage::getSingleton('catalog/product');
        $this->assertEquals(1000, $product->getId());
    }
}