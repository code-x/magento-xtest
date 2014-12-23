<?php

require_once __DIR__.'/DabaseRollbackTest/CreateProduct.php';

/**
 * Class Codex_Xtest_Test_Integration_DatabaseRollbackTest
 *
 */
class Codex_Xtest_Test_Integration_DatabaseRollbackTest extends PHPUnit_Framework_TestCase
{
    public static $testSku;

    public static function setUpBeforeClass()
    {
        self::$testSku = uniqid();
        parent::setUpBeforeClass();
    }

    /**
     * Checks if databases rolls back correctly
     *
     */
    public function testProductRollback()
    {
        $product = Mage::getModel('catalog/product');

        $this->assertFalse( $product->getIdBySku( self::$testSku ) );

        $suite = new PHPUnit_Framework_TestSuite(
            'Codex_Xtest_Test_Integration_DabaseRollbackTest_CreateProduct'
        );

        $result = $suite->run();
        $this->assertEquals(0, count($result->failures()  ) );
        $this->assertEquals(1, count($result->passed()) );
        $this->assertTrue( $result->wasSuccessful() );

        $this->assertFalse( $product->getIdBySku( self::$testSku ) );
    }

}