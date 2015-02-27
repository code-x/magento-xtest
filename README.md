Xtest
=====

Simple Magento Testing Framework - still working on it. Do not use :-)

[![Build Status](https://travis-ci.org/code-x/Xtest.svg?branch=develop)](https://travis-ci.org/code-x/Xtest)

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

You do not have to call a Test directly, without an certain filename in the shell all files in the directory are passed and the result printed on the screen. To call certain test-cases you simply have to add the filename:

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/Test/DemoTest
```
### Basic Test Classes

We are provding two different Unit-Test classes. Please extend Codex\_Xtest\_Xtest\_Unit\_Frontend to create frontend tests and Codex\_Xtest\_Xtest\_Unit\_Admin to create tests concerning the magento backend.

#### Codex\_Xtest\_Xtest\_Unit\_Abstract

- dispatchUrl( $httpUrl, $postData = null )
Dispatch a url

- dispatch($route, $params = array(), $postData = null)
Dispatch a magento url

#### Codex\_Xtest\_Xtest\_Unit\_Frontend

assertPaymentMethodIsAvailable
- Checks if a payment method is available

populuateQuote
- Poplulates Magento Quote to all magento methods

setCustomerAsLoggedIn
- Sets customer as logged in 

#### Codex\_Xtest\_Xtest\_Unit\_Admin

Sets first Admin-User as logged in automatticly. 

### Mocking

Mocking models or helpers is a elementary feature to create tests.
Normally you should test an explicit function and mock all other depending stuff to have reliable results.

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
	public function testDemoMock($productIsAvailable, $expectedAvailable)
    {
        $mock = $this->getModelMock('catalog/product', array('isAvailable') );
        $mock->expects($this->any())
            ->method('isAvailable')
            ->willReturn( $productIsAvailable );
        $this->addModelMock( 'catalog/product', $mock );

        /** @var Mage_Catalog_Model_Product $product */
        $product = Mage::getModel('catalog/product');
        $this->assertEquals( $product->isAvailable(), $expectedAvailable );
    }

```

Normally you do not test your mocking results. that doesn't makes sense.

#### Helper Mocking

You could mock helper in same way

```
	public function testDemoMock($productIsAvailable, $expectedAvailable)
	{
        $mock = $this->getHelperMock('codex_demo', array('getDemoMethod') );
        $mock->expects( $this->any() )
            ->method( 'getDemoMethod' )
            ->willReturn( 'some value' );
        $this->addHelperMock('codex_demo', $mock);
    }
```

Please consider you create a mock using $this->getHelperMock() and publish your mock using $this->addHelperMock().

#### Permanent Mocking: Double

Sometimes you have a class which communicates to an external service, or something your class is doing really crazy stuff so you are not able to test other modules.
In this case you could create a permanent mock.

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

We don't like yaml. So we using magento classes to generate test data.
Normally we are using preconfigured databases so we do not have to create all our product-data before testing.

#### Configuration

To set-up fixture configuration (e.g. product sku, e-mailadress, et.) see in app/code/community/Codex/Xtest/etc/xtest.xml

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

You could change all values by creating an own xtest.xml in your module and override our values with yours.

#### Order / Quote

```
/** @var $orderFixture Codex_Xtest_Xtest_Fixture_Order */
$orderFixture = Xtest::getXtest('xtest/fixture_order');
$testOrder = $orderFixture->getTest()
```
This creates a basic test order. 


## Selenium Tests

### Database
- Will run on your current database
- Would change database, tests have to cleanup database itself! (or don't care about it)

### Using Selenium

You have to start Selenium first. We provide all required files in app/tests/selenium - not the directory app of Magento but the directory app in xtest. Just run start.sh to start it.

### Using Page-Objects

TODO: Add some stuff here

### Testing Frontend

TODO: Add some stuff here



