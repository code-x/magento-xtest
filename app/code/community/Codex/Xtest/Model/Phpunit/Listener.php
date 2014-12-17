<?php

class Codex_Xtest_Model_Phpunit_Listener implements PHPUnit_Framework_TestListener
{
    /**
     * @var array
     */
    protected $additionalFiles = array();

    protected $lastResult;

    protected $lastStatus;

    protected $suiteName;

    protected $results = array();

    protected $count = array();

    protected $dir;

    public function __construct()
    {
        $this->dir = Mage::getBaseDir() . DS . 'var' . DS . 'tests' . DS . date('Y-m-d H:i:s');
        mkdir($this->dir, 0777, true);
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->lastResult = $e;
        $this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_ERROR;
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->lastResult = $e;
        $this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE;
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->lastResult = $e;
        $this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE;
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->lastResult = $e;
        $this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED;
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->lastResult = $e;
        $this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED;
    }

    public function getDocComment(PHPUnit_Framework_Test $test)
    {
        try {
            $class = new ReflectionClass($test);
            $method = $class->getMethod($test->getName(false));
            $docComment = $method->getDocComment();
            $docComment = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docComment);
            $docComment = "\n" . $docComment;
            $endOfDescription = strpos($docComment, "\n@");
            if ($endOfDescription !== false) {
                $docComment = substr($docComment, 0, $endOfDescription);
            }
            $docComment = trim($docComment);
        } catch (Exception $e) {

        }
        return $docComment;
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {

        $testName = PHPUnit_Util_Test::describe($test);

        // store in result array
        $currentArray =& $this->results[$this->suiteName];


        if (is_null($this->lastStatus)) {
            $this->lastStatus = PHPUnit_Runner_BaseTestRunner::STATUS_PASSED;
        }

        $result = array(
            'testName' => $testName,
            'time' => $time,
            'exception' => $this->lastResult,
            'status' => $this->lastStatus,
            'description' => $this->getDocComment($test),
            'screenshots' => array()
        );

        if (method_exists($test, 'getScreenshots')) {
            foreach ($test->getScreenshots() AS $i => $item) {
                file_put_contents($this->dir . DS . $i . '.png', $item[1]);
                $result['screenshots'][$i] = $item[0];
            }
        }

        if (method_exists($test, 'getInfo')) {
            $result['info'] = $test->getInfo();
        }

        if (isset($this->count[$this->lastStatus])) {
            $this->count[$this->lastStatus]++;
        } else {
            $this->count[$this->lastStatus] = 1;
        }

        $currentArray['__tests'][$testName] = $result;

        $this->lastResult = null;
        $this->lastStatus = null;
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $this->level++;
        $name = PHPUnit_Util_Test::describe($suite);
        if (empty($name)) {
            $name = get_class($suite);
        }
        $this->suiteName = $name;
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        file_put_contents($this->dir . DS . 'log.json', json_encode($this->results));
    }


}