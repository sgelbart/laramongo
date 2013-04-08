<?php

class ContentRepositoryTest extends TestCase
{
    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'contents' );
    }

    public function testShouldSearch()
    {
        testContentProvider::saved('valid_article');
        testContentProvider::saved('invalid_article');
        testContentProvider::saved('hidden_valid_article');

        $repo = new ContentRepository;

        // Search for all
        $result = $repo->search();
        $this->assertEquals(Content::all()->toArray(), $result->toArray());

        // Search by name
        $result = $repo->search( 'Materia' );
        $equivalentQuery = ['name'=> new \MongoRegex('/^Materia/i')];
        $this->assertEquals(Content::where($equivalentQuery)->toArray(), $result->toArray());
    }
}
