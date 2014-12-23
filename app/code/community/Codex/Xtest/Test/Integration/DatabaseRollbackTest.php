<?php

/**
 * Class Codex_Xtest_Test_Integration_DatabaseRollbackTest
 *
 */
class Codex_Xtest_Test_Integration_DatabaseRollbackTest extends Codex_Xtest_Xtest_Unit_Frontend
{
    const TEST_SKU = 'DEL-50873';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    /**
     * Checks if databases rolls back correctly
     *
     * @uses Codex_Xtest_Test_Integration_DabaseRollbackTest_CreateProduct
     */
    public function testProductRollback()
    {
        $product = Mage::getModel('catalog/product');
        $this->assertFalse( $product->getIdBySku( self::TEST_SKU ) );

        $cmd = Mage::getBaseDir().'/tests/phpunit.phar '.escapeshellarg(__DIR__.'/DabaseRollbackTest/CreateProductTest.php');

        $result = $return_var = null;
        exec('php '.$cmd, $result, $return_var);
        $this->assertEquals(0, $return_var ); // return 0 when test is succesfully

        $this->assertFalse( $product->getIdBySku( self::TEST_SKU ) );
    }

}