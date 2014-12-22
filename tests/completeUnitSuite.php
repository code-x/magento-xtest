<?php

/**
 * Static test suite.
 *
 * @author Fabrizio Branca
 */
class completeSuite  {

    /**
     * Create suite from path by searching (recursive) for all Test(case).php files
     *
     * @param string $path
     * @return PHPUnit_Framework_TestSuite|false
     */
    public static function createSuiteFromPath($path) {
        $tmpSuite = new PHPUnit_Framework_TestSuite();
        $somethingWasAdded = false;
        $paths = array();
        $files = array();

        // collect paths and files first
        foreach (new DirectoryIterator($path) as $fileInfo) { /* @var $fileInfo SplFileInfo */
            $fileName = $fileInfo->getFilename();
            $pathName = $fileInfo->getPathname();

            if ($fileName[0] == '.') { // exclude ".", "..", ".svn",...
                continue;
            }

            // directories and links pointing to directories
            if ($fileInfo->isDir() || ($fileInfo->isLink() && is_dir($fileInfo->isLink()) )) {
                $paths[] = $pathName;
            } elseif ($fileInfo->isFile()) {
                if ((substr(strtolower($fileName), -12) == 'testcase.php') || (substr(strtolower($fileName), -8) == 'test.php')) {

                    if( stripos( file_get_contents($pathName), 'ecomdev' ) === false ) {
                        // do not add ecomdev phpunit
                        if( stripos( file_get_contents($pathName), '_selenium_' ) === false ) {
                            // .. and selenium
                            $files[] = $pathName;
                        }
                    }
                }
            }
        }

        // sort them alphabetically
        sort($paths);
        sort($files);

        // create subsuites for all directories found
        foreach ($paths as $pathName) {
            $subSuite = self::createSuiteFromPath($pathName);
            if ($subSuite) {
                $tmpSuite->addTestSuite($subSuite);
                $somethingWasAdded = true;
            }
        }

        // add tests for all files found
        foreach ($files as $pathName) {
            $output = self::getRelativeRealpath($pathName);
            echo "Added test file: $output\n";
            $tmpSuite->addTestFile($pathName);
            $somethingWasAdded = true;
        }

        return ($somethingWasAdded ? $tmpSuite : false);
    }

    /**
     * Get relative path
     *
     * @static
     * @param $path
     * @return mixed|string
     */
    public static function getRelativeRealpath($path) {
        $path = realpath($path);
        $path = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $path);
        return $path;
    }

    /**
     * Creates the suite.
     *
     * Run single test or test from special folders by adding
     * --testFile <path>
     * or
     * --testPath <path>
     * to the phpunit call
     *
     * If no parameter is set it takes all tests from the current directory
     *
     * @return PHPUnit_Framework_TestSuite|false
     */
    public static function suite() {

        $tmpSuite = new PHPUnit_Framework_TestSuite();
        $tmpSuite = new PHPUnit_Framework_TestSuite();;

        $testSuiteLocal = self::createSuiteFromPath( Mage::getConfig()->getOptions()->getCodeDir().DS.'local' );
        $testSuiteCommunity = self::createSuiteFromPath( Mage::getConfig()->getOptions()->getCodeDir().DS.'community' );

        $tmpSuite->addTestSuite( $testSuiteLocal );
        $tmpSuite->addTestSuite( $testSuiteCommunity );

        return $tmpSuite;
    }
}

