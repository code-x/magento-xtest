<?php

class Codex_Xtest_Test_Unit_AdminTest extends Codex_Xtest_Xtest_Unit_Admin
{

    public function testDispatchCatalogProduct()
    {
        $this->dispatch('admin/catalog_product');
        $this->assertLayoutBlockExists('products_list');
    }

}