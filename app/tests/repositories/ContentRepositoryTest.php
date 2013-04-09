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

    public function testShouldGetPageCount()
    {
        testContentProvider::saved('valid_article');
        testContentProvider::saved('invalid_article');
        testContentProvider::saved('hidden_valid_article');

        $repo = new ContentRepository;
        $result = $repo->search();

        $repo->perPage = 6;
        $this->assertEquals(1, $repo->pageCount($result));

        $repo->perPage = 2;
        $this->assertEquals(2, $repo->pageCount($result));

        $repo->perPage = 1;
        $this->assertEquals(3, $repo->pageCount($result));
    }

    public function testShouldPaginate()
    {
        testContentProvider::saved('valid_article');
        testContentProvider::saved('invalid_article');
        testContentProvider::saved('hidden_valid_article');

        $repo = new ContentRepository;
        
        // 6 per page, first page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 6;
        $page = 1;
        $should_be = $testingResult
            ->limit(6)
            ->skip( ($page-1)*6 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());

        // 2 per page, first page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 2;
        $page = 1;
        $should_be = $testingResult
            ->limit(2)
            ->skip( ($page-1)*2 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());

        // 2 per page, second page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 2;
        $page = 2;
        $should_be = $testingResult
            ->limit(2)
            ->skip( ($page-1)*2 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());

        // 1 per page, second page
        $testingResult = $repo->search();
        $result = $repo->search();

        $repo->perPage = 1;
        $page = 2;
        $should_be = $testingResult
            ->limit(1)
            ->skip( ($page-1)*1 );

        $this->assertEquals($should_be->toArray(), $repo->paginate($result, $page)->toArray());
    }
}
