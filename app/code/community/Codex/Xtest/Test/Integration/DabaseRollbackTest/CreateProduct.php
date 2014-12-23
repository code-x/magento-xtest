<?php

class Codex_Xtest_Test_Integration_DabaseRollbackTest_CreateProduct extends Codex_Xtest_Xtest_Unit_Frontend
{

    public function testCreateProduct()
    {
        $testSku = Codex_Xtest_Test_Integration_DatabaseRollbackTest::$testSku;

        $product = Mage::getModel('catalog/product');

        $product->setSku( $testSku );
        $product->setName( 'name' );
        $product->setDescription( 'desc' );
        $product->setShortDescription( 'desc' );
        $product->setPrice(0);
        $product->setWeight(0);
        $product->setTypeId('simple');
        $product->setAttributeSetId(4);
        $product->save();

        $product = Mage::getModel('catalog/product');
        $this->assertNotFalse( $product->getIdBySku( $testSku ) );
    }

}