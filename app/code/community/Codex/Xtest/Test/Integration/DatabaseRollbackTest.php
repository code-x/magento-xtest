<?php

class Codex_Xtest_Test_Integration_DatabaseRollbackTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    protected static $testSku;

    public function testProductRollback()
    {
        $product = Mage::getModel('catalog/product');

        self::$testSku = uniqid();
        $this->assertFalse( $product->getIdBySku( self::$testSku ) );

        $suite = new PHPUnit_Framework_TestSuite(
            'ProductRollbackSub'
        );
        $result = $suite->run();
        $this->assertEquals(0, $result->failureCount() );
        $this->assertTrue( $result->wasSuccessful() );

        $this->assertFalse( $product->getIdBySku( self::$testSku ) );
    }

    public function testProductRollbackSub()
    {
        $product = Mage::getModel('catalog/product');

        $product->setSku( self::$testSku );
        $product->setName( 'name' );
        $product->setDescription( 'desc' );
        $product->setShortDescription( 'desc' );
        $product->setPrice(0);
        $product->setWeight(0);
        $product->setTypeId('simple');
        $product->setAttributeSetId(4);
        $product->save();

        $product = Mage::getModel('catalog/product');
        $this->assertNotFalse( $product->getIdBySku( self::$testSku ) );
    }

}