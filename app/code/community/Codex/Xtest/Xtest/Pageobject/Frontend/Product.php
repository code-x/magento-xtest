<?php

class Codex_Xtest_Xtest_Pageobject_Frontend_Product extends Codex_Xtest_Xtest_Pageobject_Abstract
{

    /** @var  Mage_Catalog_Model_Product */
    protected $_product;


    public function open( $product_id )
    {
        $this->_product = Mage::getModel('catalog/product')->load( $product_id );
        if( $this->_product->getId() )
        {
            $this->url( $this->_product->getProductUrl() );
            return $this;
        }
        throw new Exception("product not found");
    }

    protected function getAddToCartForm()
    {
        $eForm = $this->byId('product_addtocart_form');
        return $eForm;
    }

    public function setQty( $qty )
    {
        $eQty = $this->getAddToCartForm()->byId('qty');
        $eQty->value( $qty);
        return $this;
    }

    public function pressAddToCart()
    {
        $this->getAddToCartForm()->byCssSelector('.add-to-cart-buttons button')->click();
        return $this;
    }

    public function assertAddToCartMessageAppears()
    {
        $addToCartText = $this->byCssSelector('ul.messages li.success-msg')->text();
        $this->assertStringEndsWith( Mage::helper('checkout')->__("%s was added to your shopping cart.",''), $addToCartText );
        return $this;
    }

    public function getProductName()
    {
        return $this->byCssSelector('.product-name')->text();
    }

}