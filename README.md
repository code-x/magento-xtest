Xtest
=====

Simple Magento Testing Framework.

[![Build Status](https://travis-ci.org/code-x/Xtest.svg?branch=develop)](https://travis-ci.org/code-x/Xtest)

---------------------------------------------------------------------------------

## General

xtest is a test-suite which integrates PhpUnit into Magento. It is supporting basic unit testing and selenium testing.

xtest is designed to create project integrations tests. instead of creating all entities we are using a preconfigured database. 

## Install

To Install xtest link all files to your magento installation. We are providing a modman file to do this automatically.

## Unit-Test

unit-tests are running on current database. All changes are running in transactions so nothing may change your database. (except of MyISAM Tables which are not supporting database rollbacks)

### Get started

To start testing create a test class in your custom module your currently working on. You just have to create a directory called 'Test'. For this test create a file 'DemoTest.php' with the following content:

File: app/code/local/Codex/Demo/Test/Model/DemoTest.php

```
<?php

class Codex_Demo_Test_Model_DemoTest extends Codex_Xtest_Xtest_Unit_Frontend
{

	public function testHomePageContainsHelloWorld()
	{
		
		$this->dispatch('/');
		$this->assertContains('Hello World', $this->getResponseBody() );
	}

}
```

Run tests using following commands:

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/Test/
```

Congratulation! You have done your first unit-tests using xtest.

You do not have to call a test directly, without an certain filename in the shell all files in the directory are passed and the result printed on the screen. To call certain test-cases you simply have to add the filename:

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/Test/DemoTest
```

### Basic Test Classes

We are provding too different Unit-Test classes. Please extend Codex\_Xtest\_Xtest\_Unit\_Frontend to create frontend tests and Codex\_Xtest\_Xtest\_Unit\_Admin. 

#### Codex\_Xtest\_Xtest\_Unit\_Abstract

- *dispatchUrl( $httpUrl, $postData = null )*: Dispatch a url
- *dispatch($route, $params = array(), $postData = null)*: Dispatch a magento url

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

In this example we have a Model codex\_api/service\_customer which has a method userExists. This method returns true when a user exists in a different api, false when not.

So, let us start mocking - jay! :-D

```
class Codex_Demo_Test_Model_MockTest extends Codex_Xtest_Xtest_Unit_Frontend
{

	public function demoProvider()
	{
		return array(
			array( true, true ),
			array( false, false )
		);
	}
	
	/**
	 *
	 * @dataProvider demoProvider
	 **/
	public function testDemoMock($userExistsInApi, $expectedUserExists)
	{
		$mock = $this->getMock('codex_api/service_customer', array('userExists') );
		$mock->expects($this->any())
			->method('userExists')
			->willReturn( $userExistsInApi );
		$this->addModelMock( 'codex_api/service_customer', $mock );

		$api = Mage::getModel('codex_api/service_customer');
		$this->assertEqual( $userExistsInApi, $expectedUserExists );
	}

```

Normally you do not test your mocking results. that doesn't makes sense :)

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

#### Permanet Mocking: Double

Sometimes you have a class which communicates to an external service. Something your class is doing really crazy stuff so you are not able to test other modules. In this case we could create a permanet mock.

```
class Codex_Demo_Model_Crazy extends Varien_Object {
	
	public function doCrazyStuff()
	{
		die("This is crazy stuff");
	}
	
}
```

If you create a second class

```
class Codex_Demo_Test_Double_Model_Crazy extends Codex_Demo_Model_Crazy
{
	public function doCrazyStuff()
	{
		// Do nothing here
	}
}
```

xtest would use Codex\_Demo\_Test\_Double\_Model\_Crazy instead of Codex\_Demo\_Model\_Crazy. 

#### Block Mocking

Blocks should get all data from model(s) so you really don't want to mock them.

### Fixtures

I don't like yaml. So we using magento classes to generate test data.
Normally we are using preconfigured database so we do not have to create all our product-data before testing. 

#### Configuration

To set-up fixture configuration (e.g. product sku, e-mailadress, et.) see in app/code/community/Xtest/etc/xtest.xml

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
                        <region_id></region_id>
                    </billing_address>

                    <shipping_address>
                        <firstname>Xtest Firstname</firstname>
                        <lastname>Xtest Lastname</lastname>
                        <street>Xtest Street</street>
                        <city>Xtest City</city>
                        <postcode>33100</postcode>
                        <telephone>Xtest Phone</telephone>
                        <country_id>DE</country_id>
                        <region_id></region_id>
                    </shipping_address>

                </customer>

                <order>

                    <customer_id>0</customer_id>
                    <customer_data>
                        <email>devnull@code-x.de</email>
                        <firstname>Test Vorname</firstname>
                        <lastname>Test Nachname</lastname>
                    </customer_data>

                    <payment_method>
                        <method>debit</method>
                        <debit_cc_owner>Test</debit_cc_owner>
                        <debit_iban>Test</debit_iban>
                        <debit_swift>Test</debit_swift>
                        <!-- some other options-->
                    </payment_method>

                    <shipping_method>
                        <method>flatrate_flatrate</method>
                    </shipping_method>

                    <billing_address>
                        <firstname>Xtest Firstname</firstname>
                        <lastname>Xtest Lastname</lastname>
                        <street>Xtest Street</street>
                        <city>Xtest City</city>
                        <postcode>33100</postcode>
                        <telephone>Xtest Phone</telephone>
                        <country_id>DE</country_id>
                        <region_id></region_id>
                    </billing_address>

                    <shipping_address>
                        <firstname>Xtest Firstname</firstname>
                        <lastname>Xtest Lastname</lastname>
                        <street>Xtest Street</street>
                        <city>Xtest City</city>
                        <postcode>33100</postcode>
                        <telephone>Xtest Phone</telephone>
                        <country_id>DE</country_id>
                        <region_id></region_id>
                    </shipping_address>

                    <items>
                        <!--
                        <item>
                            <qty>1</qty>
                            <sku></sku>
                        </item>
                        -->
                    </items>

                </order>
            </fixtures>
        </xtest>
    </default>
</config>
```

You could change all values creating a own xtest.xml in your module and override our values with yours.  

#### Order / Quote

```
/** @var $orderFixture Codex_Xtest_Xtest_Fixture_Order */
$orderFixture = Xtest::getXtest('xtest/fixture_order');
$testOrder = $orderFixture->getTest();
```

This creates a basic test order. 

#### Customer

```
/** @var $customerFixture Codex_Xtest_Xtest_Fixture_Customer */
$customerFixture = Xtest::getXtest('xtest/fixture_customer');
$testCustomer = $customerFixture->getTest()
```

This creates a test customer.

### Mail

Xtest is not sending a mail. All mails a queued. You can read mail queue this way: 

```
/** @var $mailqueue Codex_Xtest_Xtest_Helper_Mailqueue */
$mailqueue = Xtest::getXtest('xtest/helper_mailqueue');

print_r( $mailqueue->getQueue() );
```         

In addition we are providing some usefull assert:
- ```$this->assertMailTemplateIdSent( $yourTemplateId );```
- ```$this->assertMailsSent( $yourMailsSentCount )```

### Examples

#### Create Order, send order email and check if mail is sent

```
class Codex_Demo_Model_DemoTest extends Codex_Xtest_Xtest_Unit_Frontend
{
	public function testCreateOrderMail()
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
class Codex_Demo_Model_DemoTest extends Codex_Xtest_Xtest_Unit_Frontend
{
	public function testHomePageContainsNewProducts()
	{
		$this->dispatch('/');
		$this->assertLayoutBlockExists('content');
	}
}
```

#### Render-HTML

When selenium-server is running you could create screenshots of your html.

```
class Codex_Demo_Test_Selenium_HomepageTest extends Codex_Xtest_Xtest_Unit_Frontend {
	public function testRenderHomepage()
	{
		$this->dispatch('/');
		$this->renderHtml('homePage', $this->getResponseBody() );
	}
}
```

This is quite cool to take screenshots from customer/account because you are able to mock some data:

```
class Codex_Demo_Test_Selenium_HomepageTest extends Codex_Xtest_Xtest_Unit_Frontend {
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

You could open screenshots (and tests results) by browsing to http://localhost/YourProject/htdocs/tests/view/

## Selenium Tests

All selenium tests a running against your current database. NOTHING could be reverted. You have to clean up data by yourself! (or you do not care about cleaning up..)

### Using Selenium

You have to start Selenium first. We provide all required files in app/tests/selenium. Just run start.sh to start it. 

```
cd htdocs/tests/selenium
./start.sh
```

### Page-Objects

We are providing some basic page objects to simplify handling selenium tests.
Let us start with some really crappy testing: onepage checkout progress.

```
<?php

class Codex_Demo_Test_Selenium_CheckoutTest extends Codex_Xtest_Xtest_Selenium_TestCase
{

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

Before testing you have to extend your local xtest configuration with some test-data.

```
	[..]
	<default>
        <xtest>

            <selenium>

                <checkout>

                    <customer>
                        <email>devnull@code-x.de</email>
                        <firstname>Vorname</firstname>
                        <lastname>Nachname</lastname>
                    </customer>

                    <addtocart>

                        <!--
                        <product>
                            <sku>test-01</sku>
                            <qty>1</qty>
                        </product>
                        -->

                    </addtocart>

                    <billing_address>

                        <firstname>test vornanme</firstname>
                        <lastname>test name</lastname>
                        <company>firma</company>
                        <telephone>123456</telephone>
                        <street1>Teststraße 32</street1>
                        <city>Teststadt</city>
                        <postcode>33100</postcode>
                        <use_for_shipping_no>1</use_for_shipping_no>

                    </billing_address>

                    <shipping_address>

                    </shipping_address>

                    <shipping_method>
                        <method>flatrate_flatrate</method>
                    </shipping_method>

                    <payment_method>
                        <method>debit</method>

                        <kontoinhaber>Inhaber Test</kontoinhaber>
                        <swiftcode>switcode Test</swiftcode>
                        <iban>Iban Test</iban>
                    </payment_method>

                </checkout>


            </selenium>

        </xtest>
    </default>
    [..]
```
    
#### Start selenium testing

We are providing a basic selenium set in tests/selenium.

Start Selenium Server
```
cd htdocs/tests/selenium
./start.sh 
```

Now you can start it, jiha.

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/Test/Selenium/CheckoutTest.php --browser firefox --breakpoints 450x800,1280x1024
```

Now tests is running using browser firefox and taking screenshots at a width of 450 and 1280px.

Tipp: If you debugging tests you could use parameter --debug so browser window is not closing so fast :-)


