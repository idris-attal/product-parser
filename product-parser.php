<?php

// represent product from the CSV file
class Product
{
    public $make;
    public $model;
    public $colour;
    public $capacity;
    public $network;
    public $grade;
    public $condition;

    public function __construct($make = null, $model = null, $colour = null, $capacity = null, $network = null, $grade = null, $condition = null)
    {
        $this->make = $make;
        $this->model = $model;
        $this->colour = $colour;
        $this->capacity = $capacity;
        $this->network = $network;
        $this->grade = $grade;
        $this->condition = $condition;
    }
}

// parser class
class Parser
{
    public $argvValue;

    public function __construct($argv)
    {
        global $argv;
        $this->argvValue = $argv;
    }

    public function parseFile()
    {
        global $argv;

        if (isset($this->argvValue) && count($this->argvValue) >= 5) {
            $filename = $this->argvValue[2];
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            switch (strtolower($extension)) {
                case 'csv':
                    $this->parseCSV($filename);
                    break;
                case 'json':
                    $this->parseJSON($filename);
                    break;
                case 'xml':
                    $this->parseXML($filename);
                    break;
                default:
                    throw new Exception("Unsupported file format: $extension");
            }

        } else {
            throw new Exception("Please provide a valid expected flags using the '--file' and '--unique-combinations' as a command-line arguments.\n");
        }
    }

    // CSV parsing logic here
    public function parseCSV($filename)
    {
        $file = fopen($filename, "r");

        if ($file !== false) {
            // Reading header to get the column names
            $headers = fgetcsv($file);

            while (($row = fgetcsv($file)) !== false) {
                // Continue rest of the processing
                $product = $this->createProduct($headers, $row);
                $this->displayProduct($product);
                $this->countUniqueCombinations($product);
            }
            fclose($file);

            // Write unique combinations to a file
            $this->writeUniqueCombinationsToFile($this->argvValue[4] ?? "combination_count.csv");
        } else {
            echo "Error opening file: $filename";
        }
    }

    private function createProduct($headers, $row)
    {
        // required fields
        $requiredFields = ['brand_name', 'model_name'];

        foreach ($requiredFields as $requiredField) {
            $index = array_search($requiredField, $headers);
            if ($index === false || !isset($row[$index])) {
                throw new Exception("Required field '$requiredField' not found in the row.");
            }
        }

        // Map headers to desired headers
        $headerMapping = [
            'brand_name' => 'make',
            'model_name' => 'model',
            'colour_name' => 'colour',
            'gb_spec_name' => 'capacity',
            'network_name' => 'network',
            'grade_name' => 'grade',
            'condition_name' => 'condition',
        ];

        $product = new Product();

        foreach ($headers as $index => $header) {
            $value = $row[$index] ?? ''; // Use default value if the column is missing
            $product->{$headerMapping[$header]} = $value;
        }

        return $product;
    }

    private function displayProduct($product)
    {
        // Display product information
        // print_r($product);
    }

    private $uniqueCombinations = [];
    private function countUniqueCombinations($product)
    {
        // Count unique combinations
        $combinationKey = json_encode($product);
        $this->uniqueCombinations[$combinationKey] = ($this->uniqueCombinations[$combinationKey] ?? 0) + 1;
    }

    private function writeUniqueCombinationsToFile($outputFilename)
    {
        // Write unique combinations to the file
        $file = fopen($outputFilename, "w");

        if ($file !== false) {
            // Write header
            $header = array_merge(array_keys(json_decode(key($this->uniqueCombinations), true)), ['count']);
            fputcsv($file, $header);

            foreach ($this->uniqueCombinations as $combination => $count) {
                $data = json_decode($combination, true);
                $data['count'] = $count;
                fputcsv($file, $data);
            }

            fclose($file);
        } else {
            echo "Error opening file for writing: $outputFilename";
        }
    }

    // TODO for future development...
    private function parseJSON($filename)
    {
        // TODO...JSON parsing logic here
    }

    private function parseXML($filename)
    {
        // TODO.. XML parsing logic here
    }
}

// calling for usage
$parser = new Parser($argv);
$parser->parseFile();
