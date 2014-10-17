<?php

class Codex_Xtest_Xtest_Pageobject_Frontend_Cart extends Codex_Xtest_Xtest_Pageobject_Abstract
{

    public function open()
    {
        $this->url( Mage::getUrl('checkout/cart') );
    }

    public function getShoppingCartTable()
    {
        return $this->byId('shopping-cart-table');
    }

    public function getCartForm()
    {
        return $this->byCssSelector('div.cart form');
    }

    public function getCouponForm()
    {
        return $this->byCssSelector('discount-coupon-form');
    }

    public function getItems()
    {
        $result = array();

        $cartTable = $this->getShoppingCartTable();
        $trList = $this->findElementsByCssSelector( 'tr', $cartTable );
        foreach( $trList AS $tr )
        {
            /** @var PHPUnit_Extensions_Selenium2TestCase_Element $tr */

            if( $item_id = $this->getItemId( $tr ) )
            {
                $result[ $item_id ] = array(
                    'tr' => $tr,
                    'product_price' => $tr->byCssSelector('.product-cart-price .price')->text(),
                    'row_total' => $tr->byCssSelector('.product-cart-total .price')->text(),
                    'name' => $tr->byCssSelector('.product-name'),
                    //'qty' => $tr->byName('cart['.$item_id.'][qty]')->value(), // TODO
                );
            }

        }

        return $result;
    }

    public function setQty( $item_id, $qty )
    {
        $qty = $this->byName('cart['.$item_id.'][qty]');
        $qty->value( $qty );
    }

    public function getGrandTotal()
    {
        $prices = $this->byCssSelector('.shopping-cart-totals-table td .price');
        $grand_total = end( $prices ); // Last Element is Grand-Total
        return $grand_total->text();
    }

    public function clickProceedCheckout()
    {
        $this->byCssSelector('.btn-proceed-checkout')->click();
    }

    public function setCouponCode( $code )
    {
        $this->getCouponForm()->byId('coupon_code')->value( $code );
    }

    public function getCouponCode( $code )
    {
        return $this->getCouponForm()->byId('coupon_code')->value();
    }

    public function submitCouponForm()
    {
        $this->getCouponForm()->submit();
    }

    /**
     * Extracts Item-Id from delete cart-item url
     *
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $tr
     * @return bool|int
     */
    protected function getItemId( PHPUnit_Extensions_Selenium2TestCase_Element $tr )
    {
        $aList = $this->findElementsByCssSelector('a', $tr);
        foreach( $aList AS $a )
        {
            /** @var PHPUnit_Extensions_Selenium2TestCase_Element $a */
            if( preg_match('#checkout/cart/delete/id/([0-9]*)/#siU', $a->attribute('href'), $matches ) ) {
                return $matches[1];
            }
        }
        return false;
    }

}

