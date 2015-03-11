Xtest
=====

Simple Magento Testing Framework.

[![Build Status](https://travis-ci.org/code-x/Xtest.svg?branch=develop)](https://travis-ci.org/code-x/Xtest)

http://xtest-mage.com/

---------------------------------------------------------------------------------

## General

xtest is a test-suite that integrates PHPUnit into Magento. It supports basic unit testing as well as selenium testing.

xtest is designed to create integrations tests for projects. Instead of creating all entities we are using a preconfigured database. 

## Install

To Install xtest link all files to your magento installation. We are providing a modman file to do this automatically.

## Unit-Test

Unit tests run on the current database. All changes runs in transactions so nothing may change your database. (Except of MyISAM Tables which are not supporting database rollbacks)

### Get started

*All samples are taken from https://github.com/code-x/xTest.Demo. We also provide a demo video https://www.youtube.com/watch?v=rPyhS_neY6k*

To start testing create a test class in your custom module your currently working on. You just have to create a directory called `Test`. Then create a file `HomepageControllerTest.php` with the following content:

File: app/code/local/Codex/Demo/Test/Controller/HomepageControllerTest.php

```
<?php

class Codex_Demo_Test_Controller_HomepageControllerTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    /**
     *
     */
    public function testHomePageContainsNewProducts()
    {
        $this->dispatch('/');

        // Checks Layout Wrapper exists
        $this->assertLayoutBlockExists('cms.wrapper');

        // Checks page contains some content
        $this->assertContains('New Products', $this->getResponseBody());

    }

}
```

Run tests using following commands:

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/
```

Congratulations! You've just completed your first unit test using xtest.

You do not have to call a test directly, without an certain filename in the shell all files in the directory are passed and the result printed on the screen. To call certain test-cases you simply have to add the filename:

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/Test/Controller/HomepageControllerTest.php
```

### Parameter

--store_code - store code thats bootet on frontend-tests
--external -- run tests thats have an @external annotation also
--disable_double - do not use double mocks

#### Selenium based
--browser - Sets Browser thats used in selenium tests (must be available)
--breakpoints - define repsonsive breakpoints eg. "1024x800,1280x1024"
--debug -- do not close browser window on exception


### Configuration

To set-up fixture and selenium configuration (e.g. product sku, e-mailadress, etc) see in app/code/community/Codex/Xtest/etc/xtest.xml
You could change all values by creating an own xtest.xml in your module and override our values with yours.

For magento sample data you could use this one:

```
<config>
    <default>
        <xtest>
            <selenium>
                <screenshot>
                    <breakpoints>450x1024,1280x1024</breakpoints>
                </screenshot>
            </selenium>

            <fixtures>

                <customer>
                    <email>devnull@code-x.de</email>
                    <firstname>Test Vorname</firstname>
                    <lastname>Test Nachname</lastname>

                    <billing_address>
                        <firstname>Xtest Firstname</firstname>
                        <lastname>Xtest Lastname</lastname>
                        <street>Xtest Street</street>
                        <city>Xtest City</city>
                        <postcode>33100</postcode>
                        <telephone>Xtest Phone</telephone>
                        <country_id>DE</country_id>
                        <region_id>88</region_id>
                    </billing_address>

                    <shipping_address>
                        <firstname>Xtest Firstname</firstname>
                        <lastname>Xtest Lastname</lastname>
                        <street>Xtest Street</street>
                        <city>Xtest City</city>
                        <postcode>33100</postcode>
                        <telephone>Xtest Phone</telephone>
                        <country_id>DE</country_id>
                        <region_id>88</region_id>
                    </shipping_address>

                </customer>

                <order>

                    <customer_id>0</customer_id>
                    <customer_data>
                        <email>devnull@code-x.de</email>
                        <firstname>Test Firstname</firstname>
                        <lastname>Test Lastname</lastname>
                    </customer_data>

                    <payment_method>
                        <method>cashondelivery</method>
                        <!-- some other options-->
                    </payment_method>

                    <shipping_method>
                        <method>ups_XPD</method>
                    </shipping_method>

                    <billing_address>
                        <firstname>Xtest Firstname</firstname>
                        <lastname>Xtest Lastname</lastname>
                        <street>Xtest Street</street>
                        <city>Xtest City</city>
                        <postcode>33100</postcode>
                        <telephone>Xtest Phone</telephone>
                        <country_id>DE</country_id>
                        <region_id>88</region_id>
                    </billing_address>

                    <shipping_address>
                        <firstname>Xtest Firstname</firstname>
                        <lastname>Xtest Lastname</lastname>
                        <street>Xtest Street</street>
                        <city>Xtest City</city>
                        <postcode>33100</postcode>
                        <telephone>Xtest Phone</telephone>
                        <country_id>DE</country_id>
                        <region_id>88</region_id>
                    </shipping_address>

                    <items>

                        <item>
                            <qty>1</qty>
                            <sku>abl004</sku>
                        </item>

                    </items>

                </order>
            </fixtures>

            <selenium>

                <checkout>

                    <customer>
                        <email>selenium@code-x.de</email>
                        <firstname>Firstname</firstname>
                        <lastname>Lasntname</lastname>
                    </customer>

                    <addtocart>

                        <product_1>
                            <sku>abl004</sku>
                            <qty>1</qty>
                        </product_1>

                    </addtocart>

                    <billing_address>

                        <firstname>test firstname</firstname>
                        <lastname>test lastname</lastname>
                        <company>company</company>
                        <telephone>123456</telephone>
                        <street1>Teststreet 32</street1>
                        <city>Testcity</city>
                        <postcode>33100</postcode>
                        <use_for_shipping_no>1</use_for_shipping_no>

                    </billing_address>

                    <shipping_address>
                        <!-- todo -->
                    </shipping_address>

                    <shipping_method>
                        <method>ups_XPD</method>
                    </shipping_method>

                    <payment_method>
                        <method>cashondelivery</method>
                    </payment_method>

                </checkout>

            </selenium>

        </xtest>
    </default>
</config>
```

### Basic Test Classes

We are provding two different Unit-Test classes. Please extend `Codex_Xtest_Xtest_Unit_Frontend` to create frontend tests and `Codex_Xtest_Xtest_Unit_Admin` to create tests concerning the Magento backend.

#### Codex\_Xtest\_Xtest\_Unit\_Abstract

- *dispatchUrl( $httpUrl, $postData = null )*: Dispatch a url
- *dispatch($route, $params = array(), $postData = null)*: Dispatch a magento url

- dispatch($route, $params = array(), $postData = null): Dispatch a magento url

#### Codex\_Xtest\_Xtest\_Unit\_Frontend

- *assertPaymentMethodIsAvailable*: Checks if a payment method is available
- *populuateQuote*: Poplulates Magento Quote to all magento methods
- *setCustomerAsLoggedIn*: Sets customer as logged in

#### Codex\_Xtest\_Xtest\_Unit\_Admin

Sets first Admin-User as logged in automatically.

### Mocking

Mocking models or helpers is a elementary feature to create tests.
Normally you should test a explicit function and mock all other depending stuff to have reliable results.

#### Model Mocking

In this example we have a Model `catalog/product` which has a method `isSaleable`. This method depends on `isAvailable` that returns true when product is available, false when not.

So, let us start mocking.

```
<?php

class Codex_Demo_Test_Model_ProductTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    public function demoProvider()
    {
        return array(
            array( true, true ),
            array( false, false )
        );
    }

    /**
     * As catalog/product Model
     * - when product is not available
     * - then it should not be saleable
     *
     * @dataProvider demoProvider
     **/
    public function testDemoMock($productIsAvailable, $expectedSaleable)
    {
        $mock = $this->getModelMock('catalog/product', array('isAvailable') );
        $mock->expects($this->any())
            ->method('isAvailable')
            ->willReturn( $productIsAvailable );
        $this->addModelMock( 'catalog/product', $mock );

        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::getModel('catalog/product');
        $this->assertEquals( $product->isSalable(), $expectedSaleable );
    }

}
```

Normally you do not test your mocking results. Rather you have to mock depending models to test your own model.
Here we have create a simple mocking example.

#### Helper Mocking

You could mock helper in same way

```
$mock = $this->getHelperMock('codex_demo', array('getDemoMethod') );
$mock->expects( $this->any() )
    ->method( 'getDemoMethod' )
    ->willReturn( 'some value' );
$this->addHelperMock('codex_demo', $mock);
```

Please consider you create a mock using $this->getHelperMock() and publish your mock using $this->addHelperMock().

#### Permanent Mocking: Double

Sometimes you have a class which communicates to an external service, or something your class is doing really crazy stuff so you are not able to test other modules.
In this case you could create a permanent mock.

```
<?php

class Codex_Demo_Model_Weather extends Varien_Object
{

    protected function _apiCall($city, $country)
    {
        return file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$city.','.$country);
    }

    /**
     * Return weather for city eg. "broken clouds"
     *
     * @param $city
     * @param $country_id
     * @return mixed
     */
    public function getWeather( $city, $country_id )
    {
        $data = json_decode( $this->_apiCall( $city, $country_id ), true );
        return $data['weather'][0]['description'];
    }

}
```

If you create a second class

```
class Codex_Demo_Test_Double_Model_Weather extends Codex_Demo_Model_Weather
{
    protected function _apiCall($city, $country)
    {
        return '{ "coord": { "lon": 8.75, "lat": 51.72 }, "sys": { "type": 3, "id": 177301, "message": 0.0283, "country": "DE", "sunrise": 1425190178, "sunset": 1425229494 }, "weather": [ { "id": 803, "main": "Clouds", "description": "broken clouds", "icon": "04d" } ], "base": "cmc stations", "main": { "temp": 283.41, "humidity": 65, "pressure": 1001, "temp_min": 283.15, "temp_max": 283.55 }, "wind": { "speed": 1, "gust": 3, "deg": 180 }, "rain": { "3h": 0 }, "clouds": { "all": 64 }, "dt": 1425221237, "id": 2855745, "name": "Paderborn", "cod": 200 }';
    }

}
```

xtest is going to use `Codex_Demo_Test_Double_Model_Weather` instead of Codex_Demo_Model_Weather.
This is helpfully because your tests not depending on a external service.

#### Block Mocking

Blocks should get all data from model(s) so you really don't want to mock them.

### Fixtures

We don't like yaml. So we using magento classes to generate test data.
Normally we are using preconfigured databases so we do not have to create all our product-data before testing.

#### Order / Quote

This will create a basic test order.

```
/** @var $orderFixture Codex_Xtest_Xtest_Fixture_Order */
$orderFixture = Xtest::getXtest('xtest/fixture_order');
$testOrder = $orderFixture->getTest();
```

#### Customer

This will create a test customer.

```
/** @var $customerFixture Codex_Xtest_Xtest_Fixture_Customer */
$customerFixture = Xtest::getXtest('xtest/fixture_customer');
$testCustomer = $customerFixture->getTest()
```

### Mailing

All mails during your work with Xtest are not beeing send; the are all queued and can be viewed for total control.
You can access the mail queue this way:

```
/** @var $mailqueue Codex_Xtest_Xtest_Helper_Mailqueue */
$mailqueue = Xtest::getXtest('xtest/helper_mailqueue');

print_r( $mailqueue->getQueue() );
```         

In addition Xtest provids some usefull asserts:
- ```$this->assertMailTemplateIdSent( $yourTemplateId );```
- ```$this->assertMailsSent( $yourMailsSentCount )```

### Examples

#### Create Order, send order email and check if mail is sent

```
<?php

class Codex_Demo_Test_Integration_OrderTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    /**
     * As Customer
     * - when i plaved a order
     * - then I should reveice a new order email
     */
    public function testOrderMail()
    {
        /** @var $orderFixture Codex_Xtest_Xtest_Fixture_Order */
        $orderFixture = Xtest::getXtest('xtest/fixture_order');
        $testOrder = $orderFixture->getTest();

        $testOrder->sendNewOrderEmail();

        $this->assertMailsSent( 1 );
        $this->assertMailTemplateIdSent( 'sales_email_order_template' );
    }

}
```

#### Dispatch Route, check if layout is present

```
<?php

class Codex_Demo_Test_Controller_HomepageControllerTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    /**
     * As Customer
     * - when I open Homepage
     * - I should see "New Products"
     */
    public function testHomePageContainsNewProducts()
    {
        $this->dispatch('/');

        // Checks Layout Wrapper exists
        $this->assertLayoutBlockExists('cms.wrapper');

        // Checks page contains some content
        $this->assertContains('New Products', $this->getResponseBody() );

    }

}
```

#### Render-HTML

When the selenium-server is running you have the option to take screenshots of the html. These screenshots are stored in png-format in a directory of your project.

```
class Codex_Demo_Test_Selenium_HomepageScreenshotTest extends Codex_Xtest_Xtest_Unit_Frontend
{

    public function testRenderHomepage()
    {
        $this->dispatch('/');
        $this->renderHtml('homePage', $this->getResponseBody() );
    }

}
```

This is quite comfortable to take screenshots from customer/account because you are able to mock or create some data.
All data created here would be reverted after your test because you are extending `Codex_Xtest_Xtest_Unit_Abstract`.

```
class Codex_Demo_Test_Selenium_CustomerAccountScreenshotTest extends Codex_Xtest_Xtest_Unit_Frontend {
    public function testOrderHistory()
    {
        /** @var $customerFixture Codex_Xtest_Xtest_Fixture_Customer */
        $customerFixture = Xtest::getXtest('xtest/fixture_customer');
        $customer = $customerFixture->getTest();
        $customer->setConfirmation(null);
        $customer->save();

        /** @var $orderFixture Codex_Xtest_Xtest_Fixture_Order */
        $orderFixture = Xtest::getXtest('xtest/fixture_order');

        $quote = $orderFixture->getFixtureQuote()->getTest( $customer );
        $order = $orderFixture->convertQuoteToOrder( $quote );
        $order->setState( current( Mage::getSingleton('sales/order_config')->getVisibleOnFrontStates() ) );
        $order->save();

        $this->setCustomerAsLoggedIn( $customer );

        $this->dispatch('sales/order/history');
        $this->renderHtml( 'account order history', $this->getResponseBody() );

        $this->dispatch('sales/order/view/order_id/'.$order->getId());
        $this->renderHtml( 'account order details', $this->getResponseBody() );
    }
}
```

You can view the screenshots (and test results) by browsing to http://localhost/YourProject/htdocs/tests/view/

## Selenium Tests

All selenium tests a running against your current database. NOTHING can be reverted. You have to clean up data by yourself! (or you do not care about cleaning up)
Be sure you are extending `Codex_Xtest_Xtest_Selenium_TestCase`

### Using Selenium

You have to start Selenium first. We provide all required files in htdocs/tests/selenium. Just run start.sh to start it.

```
cd htdocs/tests/selenium
./start.sh
```

### Page-Objects

We are providing some basic page objects to simplify handling selenium tests.
Let us start with some really tricky testing: onepage checkout progress.

```
<?php

class Codex_Demo_Test_Selenium_CheckoutTest extends Codex_Xtest_Xtest_Selenium_TestCase
{

    protected static $_customerEmail;
    protected static $_customerPassword;

    public function setUp()
    {
        parent::setUp();

        $customerConfig = self::getSeleniumConfig('checkout/customer');
        self::$_customerEmail = $customerConfig['email'];

        // Delete Testcustomer
        $customerCol = Mage::getModel('customer/customer')->getCollection();
        $customerCol->addFieldToFilter('email', self::$_customerEmail );
        $customerCol->walk('delete');

        // Create a new one
        $customer = Mage::getModel('customer/customer');
        $customer->setData($customerConfig);
        self::$_customerPassword = $customer->generatePassword();
        $customer->setStore( current( Mage::app()->getStores() ) ); // TODO
        $customer->setPassword( self::$_customerPassword );
        $customer->validate();
        $customer->setConfirmation(null);
        $customer->save();

        $customer->load( $customer->getId() );
        $customer->setConfirmation(null);
        $customer->save();

        $_custom_address = array (
            'firstname' => 'Test',
            'lastname' => 'Test',
            'street' => array (
                '0' => 'Sample address part1',
            ),
            'city' => 'Paderborn',
            'region_id' => '',
            'region' => '88',
            'postcode' => '33100',
            'country_id' => 'DE',
            'telephone' => '0000111',
        );
        $customAddress = Mage::getModel('customer/address');
        $customAddress->setData($_custom_address)
            ->setCustomerId($customer->getId())
            ->setIsDefaultBilling('1')
            ->setIsDefaultShipping('1')
            ->setSaveInAddressBook('1');
        $customAddress->save();
    }

    public function testOnepageCheckout()
    {

        $cartConfig = $this->getSeleniumConfig('checkout/addtocart');
        foreach( $cartConfig AS $_productData )
        {

            /** @var $productPageObject Codex_Xtest_Xtest_Pageobject_Frontend_Product */
            $productPageObject = $this->getPageObject('xtest/pageobject_frontend_product');

            $productPageObject->openBySku( $_productData['sku'] );
            $productPageObject->setQty( $_productData['qty'] );

            $productPageObject->pressAddToCart();
            $productPageObject->assertAddToCartMessageAppears();

        }

        /** @var $cartPageObject Codex_Xtest_Xtest_Pageobject_Frontend_Cart */
        $cartPageObject = $this->getPageObject('xtest/pageobject_frontend_cart');
        $cartPageObject->open();

        $cartPageObject->takeResponsiveScreenshots('products in cart');

        $this->assertEquals( count($cartConfig), count( $cartPageObject->getItems() ), 'cart is missing some items' );

        $cartPageObject->clickProceedCheckout();
        $this->assertContains('checkout/onepage/', $this->url() );

        // ---

        /** @var $checkoutPageObject Codex_Xtest_Xtest_Pageobject_Frontend_Checkout */
        $checkoutPageObject = $this->getPageObject('xtest/pageobject_frontend_checkout');

        $checkoutPageObject->takeResponsiveScreenshots('login');
        $checkoutPageObject->login( self::$_customerEmail, self::$_customerPassword );
        $checkoutPageObject->assertStepIsActive('billing');

        // ---

        $checkoutPageObject->setBillingAddress();
        $checkoutPageObject->takeResponsiveScreenshots('billing address');
        $checkoutPageObject->nextStep();

        // ---

        // TODO: Shipping Address

        // ---

        $checkoutPageObject->assertStepIsActive('shipping_method');
        $checkoutPageObject->setShippingMethod();
        $checkoutPageObject->takeResponsiveScreenshots('shipping method');
        $checkoutPageObject->nextStep();

        // ---

        $checkoutPageObject->assertStepIsActive('payment');
        $checkoutPageObject->setPaymentMethod();
        $checkoutPageObject->takeResponsiveScreenshots('payment method');
        $checkoutPageObject->nextStep();

        // ---

        $checkoutPageObject->assertStepIsActive('review');
        $checkoutPageObject->acceptAgreements();
        $checkoutPageObject->takeResponsiveScreenshots('review');
        $checkoutPageObject->nextStep();

        // ---

        $checkoutPageObject->takeResponsiveScreenshots();
        $checkoutPageObject->assertIsSuccessPage();

    }

}
```

Make sure you have configured your test-data as mentioned in chapter "Configuration".
    
#### Run selenium tests

To run your tests using firefox und taking screenshots in a width of 450px and 1280px open a console and type:

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/Test/Selenium/CheckoutTest.php --browser firefox --breakpoints 450x800,1280x1024
```

Tip: If you are debugging tests you could use parameter --debug so the browser window is not closing as fast as during the normal modus.


