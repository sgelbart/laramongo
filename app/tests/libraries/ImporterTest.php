<?php

use Zizaco\CsvToMongo\Importer;
use Keboola\Csv\CsvFile;

class ImporterTest extends TestCase {

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );

        $this->aExistentCategory( 'FamilyName' );
    }

    /**
     * Checks if the importing is sucessfull and if the errors
     * are reported correctly
     */
    public function testShouldImportAndRetrieveErrors()
    {
        $file = __DIR__.'/importer_sample.csv';

        $error_count = 0;

        // Import file
        $importer = new Importer( $file, 'Product');
        $importer->import();
        
        // Prepare to compare the database documents with
        // csv lines
        $csv_parser = new CsvFile( $file, ';' );
        $db = LMongo::connection();
        $cursor = $db->products->find();

        // Rewind the cursor iterator, the iteration will be done
        // by using next, curent methods.
        $cursor->rewind();

        // Foreach line in csv
        foreach ($csv_parser as $line) {
            if(! isset($header_read))
            {
                // For the first line, grab the header names from
                // csv to use later in document field access
                $name_field = $line[1];
                $desc_field = $line[4];
                $header_read = true;
            }
            else
            {
                // If the line was valid (with name and family)
                if(trim($line[1]) && trim($line[2]))
                {
                    // Compares the values from file and database
                    $this->assertEquals( $line[4], $cursor->current()[$desc_field] );
                    $this->assertEquals( $line[1], $cursor->current()[$name_field] );

                    // Step cursor
                    $cursor->next();
                }
                else
                {
                    $error_count++;
                }
            }
        }

        $this->assertEquals($error_count, count($importer->getErrors()));
    }

    /**
     * Clean database collection
     */
    private function cleanCollection( $collection )
    {
        $db = LMongo::connection();
        $db->$collection->drop();
    }

    /**
     * Returns an category that "exists in database".
     *
     * @param string $name
     * @return Category
     */
    private function aExistentCategory( $name = 'existentfamily' )
    {
        $category = new Category;

        $category->name = $name;
        $category->description = 'somedescription';
        $category->image = 'aimage.jpg';

        $category->save();

        return $category;
    }
}
