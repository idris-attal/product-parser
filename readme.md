# Running the Example

## To run the provided example:
    Ensure you have PHP installed.
    Save your CSV file as `example_1.csv`.
------------------------------------------------------------------------------
    Run the script using the command:
     product-parser.php --file example_1.csv --unique-combinations combination_count.csv
------------------------------------------------------------------------------

## To run the tests in provided example:
    Ensure you have PHP installed.
    use the CSV file named as `test_example_1.csv` or create yours with such name
    try to put `test_combination_count.csv` for the --unique-combinations inside the running command or just copy the command as desribed below.

------------------------------------------------------------------------------
    Run the tests script using the command: 
    php product-parser-tests.php --file test_example_1.csv --unique-combinations test_combination_count.csv
------------------------------------------------------------------------------


### Future Development
    parseJSON($filename): To be implemented for parsing JSON files.
    parseXML($filename): To be implemented for parsing XML files.    