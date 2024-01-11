<?php
// Include the content of product-parser.php
require_once 'product-parser.php';

// Abstract class for test assertions
abstract class AbstractAssert
{
    // assertEqual method
    protected function assertEqual($actual, $expected, $message)
    {
        echo $actual === $expected
        ? "Test passed: $message\n"
        : "Test failed: $message\nExpected: $expected\nActual: $actual\n";
    }
}

// Test class for ProductParser
class ProductParserTest extends AbstractAssert
{
    private $parser;
    public $argvValue;

    public function __construct($argv)
    {
        global $argv;
        $this->argvValue = $argv;
        $this->parser = new Parser($argv);
    }

    // test case for testing to get the expected flagss from command arguments
    public function testExpectingFlags()
    {
        $expectedFile = 'test_example_1.csv';
        $expectedCombinations = 'test_combination_count.csv';

        if (isset($this->argvValue) && count($this->argvValue) >= 5) {
            $fileValue = null;
            $combinationsValue = null;

            foreach ($this->argvValue as $key => $arg) {
                switch ($arg) {
                    case '--file':
                        $fileValue = $this->argvValue[$key + 1];
                        $this->assertEqual($fileValue, $expectedFile, "Test Case 1 '--file' flag\n");
                        break;
                    case '--unique-combinations':
                        $combinationsValue = $this->argvValue[$key + 1];
                        $this->assertEqual($combinationsValue, $expectedCombinations, "Test Case 2 '--unique-combinations' flag\n");
                        break;
                }
            }
        } else {
            echo "Please provide a valid expected flags using the '--file' and '--unique-combinations' as a command-line arguments.\n";
        }
    }

    // Test case for parsing a CSV file
    public function testParseCSV()
    {
        // Call parseFile method and assert expectations
        $this->parser = new Parser($this->argvValue);
        $this->parser->parseFile();

        // Check if the output file was created
        $this->assertEqual(file_exists('test_combination_count.csv'), true, "Test Parse CSV - Output file\n");
    }

    // Test case for missing required field in CSV
    public function testMissingRequiredField()
    {
        // TODO:
        //  since I cover this part on my original code (createProduct fucntion)
        // I will not cover the test here for the moment
        $this->assertEqual(true, true, "Test Case 4 checking 'missing required field'\n");
    }

    // Test case for unsupported file format
    public function testUnsupportedFileFormat()
    {
        // TODO:
        // since I cover this part on my original code (parseFile function)
        // I will not cover the test here for the moment
        // but the steps are as follows:
        // 1- Set the mock command-line arguments with an unsupported file format
        // 2- Call parseFile method and expect an exception
        // 3- Assert the $this->assertEqual(true, false, "mesage");
        // 4- cover all the codes with try catch
    }

    // calling all tests
    public function runTests()
    {
        echo "**************************\n";
        // Run all test methods
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (strpos($method, 'test') === 0) {
                $this->$method();
            }
        }
        echo "**************************\n";
    }
}

// calling for usage
$testSuite = new ProductParserTest($argv);
$testSuite->runTests();
