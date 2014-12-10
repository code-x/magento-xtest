<?php

class Codex_Xtest_Xtest_Unit_Abstract extends PHPUnit_Framework_TestCase
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * @var Mage_Core_Model_Resource
     */
    protected $_transaction;


    public function addModelMock($modelClass, $mockClassObj)
    {
        Xtest::getConfig()->addModelMock($modelClass, $mockClassObj);
        $this->assertEquals($mockClassObj, Mage::getModel($modelClass));
    }

    public function addHelperMock($helperName, $mockClassObj)
    {
        Xtest::getConfig()->addHelperMock($helperName, $mockClassObj);
        $this->assertEquals($mockClassObj, Mage::helper($helperName));

        if (strpos($helperName, '/') === false) {
            $this->assertEquals($mockClassObj, Mage::helper($helperName . '/data'));
        }
    }

    public function getModelMock(
        $originalClassName,
        $methods = array(),
        array $arguments = array(),
        $mockClassName = '',
        $callOriginalConstructor = true,
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false,
        $callOriginalMethods = false
    ) {
        return $this->getMock(
            Mage::getConfig()->getModelClassName($originalClassName),
            $methods,
            $arguments,
            $mockClassName,
            $callOriginalConstructor,
            $callOriginalClone,
            $callAutoload,
            $cloneArguments,
            $callOriginalMethods
        );
    }

    public function getHelperMock(
        $originalClassName,
        $methods = array(),
        array $arguments = array(),
        $mockClassName = '',
        $callOriginalConstructor = true,
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false,
        $callOriginalMethods = false
    ) {
        return $this->getMock(
            Mage::getConfig()->getHelperClassName($originalClassName),
            $methods,
            $arguments,
            $mockClassName,
            $callOriginalConstructor,
            $callOriginalClone,
            $callAutoload,
            $cloneArguments,
            $callOriginalMethods
        );
    }

    protected function setUp()
    {
        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $db->query('SET autocommit=0;');
        $db->beginTransaction();

        parent::setUp();
    }

    protected function runTest()
    {
        if ($this->isExternal() && !$this->allowExternal()) {
            $this->markTestSkipped('Test requires external reference');
            return false;
        }
        return parent::runTest();
    }

    /**
     * @return bool
     */
    protected function isExternal()
    {
        $class = get_class($this);
        $method = $this->getName(false);
        $reflection = new ReflectionMethod($class, $method);

        if ($docBlock = $reflection->getDocComment()) {
            return (strpos($docBlock, '@external') !== false);
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function allowExternal()
    {
        $argv = $_SERVER['argv'];
        return in_array('--external', $argv);
    }

    protected function tearDown()
    {
        /** @var $mailqueue Codex_Xtest_Xtest_Helper_Mailqueue */
        $mailqueue = Xtest::getXtest('xtest/helper_mailqueue');
        $mailqueue->reset();

        parent::tearDown();
        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $db->rollBack();
    }

    public function dispatchUrl( $httpUrl, $postData = null )
    {
        $request = new Codex_Xtest_Model_Core_Controller_Request_Http();
        $request->setBaseUrl( Mage::getBaseUrl('web', true) );
        $request->setRequestUri( $httpUrl );
        $request->setPathInfo();

        if( $postData ) {
            $request->setMethod( self::METHOD_POST );
            $request->setPost( $postData );
        }

        Mage::$headersSentThrowsException = false;
        Mage::app()->setRequest($request);

        $dispatcher = new Codex_Xtest_Model_Core_Controller_Varien_Front();
        $dispatcher->setRouter(Mage::app()->getFrontController()->getRouters());
        $dispatcher->dispatch();
    }

    public function dispatch($route, $params = array(), $postData = null)
    {
        $request = new Codex_Xtest_Model_Core_Controller_Request_Http();
        $request->setPathInfo($route);
        $request->setParams($params);
        $request->setParam('nocookie', true);


        if( $postData ) {
            $request->setMethod( self::METHOD_POST );
            if( !isset($postData['form_key']) ) {
                $postData['form_key'] = Mage::getSingleton('core/session')->getFormKey();
            }
            $request->setPost( $postData );
        }

        Mage::$headersSentThrowsException = false;
        Mage::app()->setRequest($request);

        $dispatcher = new Codex_Xtest_Model_Core_Controller_Varien_Front();
        $dispatcher->setRouter(Mage::app()->getFrontController()->getRouters());
        $dispatcher->dispatch();
    }

    public function getResponseBody()
    {
        return trim( Mage::app()->getResponse()->getBody() );
    }

    /**
     * @return Mage_Core_Model_Layout
     */
    public function getLayout()
    {
        return Mage::app()->getLayout();
    }

    public function assertLayoutBlockExists($nameInLayout)
    {
        $block = $this->getLayout()->getBlock($nameInLayout);
        $this->assertNotFalse( $block, "Block $nameInLayout not found" );
    }

    protected function assertMailsSent( $expectedMailCnt )
    {
        /** @var $mailqueue Codex_Xtest_Xtest_Helper_Mailqueue */
        $mailqueue = Xtest::getXtest('xtest/helper_mailqueue');
        $this->assertEquals($expectedMailCnt , $mailqueue->getCount(), 'wrong mailcount' );
    }

    public function setExpectedMageException($module, $exceptionMessage = '', $exceptionCode = null)
    {
        $this->setExpectedException(get_class(Mage::exception($module)), $exceptionMessage, $exceptionCode);
    }
}