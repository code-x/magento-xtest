<?php

class Codex_Xtest_Test_Integration_DabaseRollbackTest_CreateProductTest extends Codex_Xtest_Xtest_Unit_Frontend
{
    /**
     * Test if creating a product is successfully
     */
    public function testCreateProduct()
    {
        $testSku = Codex_Xtest_Test_Integration_DatabaseRollbackTest::TEST_SKU;

        $product = Mage::getModel('catalog/product');

        $product->setSku($testSku);
        $product->setName('name');
        $product->setDescription('desc');
        $product->setShortDescription('desc');
        $product->setPrice(0);
        $product->setWeight(0);
        $product->setTypeId('simple');
        $product->setAttributeSetId(4);
        $product->save();

        $product = Mage::getModel('catalog/product');
        $this->assertNotFalse($product->getIdBySku($testSku));
    }

    /**
     * Checks if product is gone after first test
     */
    public function testCreateProductStillNotExists()
    {
        $product = Mage::getModel('catalog/product');
        $this->assertFalse($product->getIdBySku(Codex_Xtest_Test_Integration_DatabaseRollbackTest::TEST_SKU));
    }
}