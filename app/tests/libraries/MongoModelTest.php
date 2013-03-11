<?php

use Zizaco\LmongoOrm\MongoModel;

class MongoModelTest extends TestCase {

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'temporary' );
    }

    /**
     * Should save document
     *
     */
    public function testShouldSaveDocument()
    {
        $document = new MongoModel;

        $document->name = 'prod';
        $document->price = 12.34;
        $document->desc = 'desc';

        $this->assertTrue( $document->save() );
        $this->assertTrue( isset($document->id) );
    }

    /**
     * Should retrieve document
     *
     */
    public function testShouldRetreiveDocument()
    {
        $existent = $this->aExistentDocument();

        $document = MongoModel::first(array('name'=>$existent->name));

        // Compares the existent document with the document found
        // Should be the same
        $this->assertEquals( 'Zizaco\LmongoOrm\MongoModel', get_class( $document ) );
        $this->assertEquals( $existent->desc, $document->desc );
    }

    /**
     * Should retrieve document by id
     *
     */
    public function testShouldRetreiveById()
    {
        $existent = $this->aExistentDocument();

        // Don't use the query array, just pass the id
        $document = MongoModel::first( $existent->id );

        // Compares the existent document with the document found
        // Should be the same
        $this->assertEquals( 'Zizaco\LmongoOrm\MongoModel', get_class( $document ) );
        $this->assertEquals( $existent->desc, $document->desc );
    }

    /**
     * Should retrieve many documents
     *
     */
    public function testShouldRetreiveManyDocuments()
    {
        $this->populateCollection(10);

        $cursor = MongoModel::all();

        // Shoud return the ORM cursor
        $this->assertEquals( 'Zizaco\LmongoOrm\OrmCursor', get_class( $cursor ) );

        // Should have 10 documents
        $this->assertEquals( 10, $cursor->count() );

        // Iterate and count
        $count = 0;
        foreach( $cursor as $document ) {
            $this->assertEquals( 'Zizaco\LmongoOrm\MongoModel', get_class( $document ) );
            ++$count;
        }

        // Check if the iteration is correct
        $this->assertEquals( 10, $count );

    }

    /**
     * Should update document
     *
     */
    public function testShouldUpdateDocument()
    {
        $document = $this->aExistentDocument();

        $document->name = 'new something';

        // Should save a document containing the id
        $this->assertTrue( $document->save() );

        // Select the document from database
        $result = MongoModel::first();

        // Both names should be 'new something'
        $this->assertEquals( $document->name, $result->name );
    }

    /**
     * Should set all attributes with array if
     * the massAssignment is empty
     *
     */
    public function testShouldSetAttributesWithArray()
    {
        $document = new MongoModel;

        $input = array(
            'name' => 'theName',
            'age' => 25,
            'value' => 8.5
        );

        $document->setAttributes( $input );

        $this->assertEquals( $input['name'], $document->getAttribute('name') );
        $this->assertEquals( $input['age'], $document->getAttribute('age') );
        $this->assertEquals( $input['value'], $document->getAttribute('value') );
    }

    /**
     * Should not set attributes that are not
     * especified in massAssignment.
     *
     */
    public function testShouldNotSetAnyAttributes()
    {
        $document = new MongoModel;

        $input = array(
            'name' => 'theName',
            'age' => 25,
            'value' => 8.5
        );

        $document->massAssignment = array('name','value');

        $document->setAttributes( $input );

        $this->assertEquals   ( $input['name'], $document->getAttribute('name') );
        $this->assertNotEquals( $input['age'], $document->getAttribute('age') );
        $this->assertEquals   ( $input['value'], $document->getAttribute('value') );
    }

    /**
     * Returns an document that "exists in database".
     *
     * @param string $name
     * @return MongoModel
     */
    private function aExistentDocument( $name = 'something' )
    {
        $document = new MongoModel;

        $document->name = 'something';
        $document->price = 12.34;
        $document->desc = 'the description';

        $document->save();

        return $document;
    }

    /**
     * Populate collection
     *
     * @param integer $howMany
     */
    private function populateCollection( $howMany = 10 )
    {
        for ($i=0; $i < $howMany ; $i++) { 
            $this->aExistentDocument('something'.$i);
        }
    }

    /**
     * Clean database collection
     */
    private function cleanCollection( $collection )
    {
        $db = LMongo::connection();
        $db->$collection->drop();
    }

}
