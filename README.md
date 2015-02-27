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

To start testing create a test class in your custom module:

File: app/code/local/Codex/Test/Model/DemoTest.php

```
class Codex_Demo_Test_Model_DemoTest extends Codex_Xtest_Xtest_Unit_Frontend
{

	public function testHomePageContainsHelloWorld()
	{
		
		$this->dispatch('/');
		$this->assertContains('Hello World', $this->getResponseBody() )
	}

}
```

Run tests using following commands:

```
cd htdocs/tests
php phpunit.phar ../app/code/local/Codex/Demo/Test/
```

Congratulation! You have done your first unit-tests using xtest.

### Basic Test Classes

We are provding too different Unit-Test classes. Please extend Codex\_Xtest\_Xtest\_Unit\_Frontend to create frontend tests and Codex\_Xtest\_Xtest\_Unit\_Admin. 

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

xtest would use Codex_Demo_Test_Double_Model_Crazy instead of Codex_Demo_Model_Crazy. 

#### Block Mocking

Blocks should get all data from model(s) so you really don't want to mock them.

#### Fixtures



## Selenium Tests

### Database
- Will run on your current database
- Would change database, tests have to cleanup database itself! (or don't care about it)

### Using Selenium

You have to start Selenium first. We provide all required files in app/tests/selenium. Just run start.sh to start it. 

### Using Page-Objects

TODO: Add some stuff here

### Testing Frontend

TODO: Add some stuff here



