<?php

use Mockery as m;
use Laramongo\SearchEngine\ElasticSearchEngine;

class ElasticSearchEngineTest extends Zizaco\TestCases\TestCase {

    protected $mockedEs;

    public function setUp()
    {
        parent::setUp();

        // Enable search engine
        Config::set('search_engine.enabled', true);

        // Prepare mocked searchEngine
        $this->mockedEs = m::mock('Es');
        $this->mockedEs->shouldReceive('setIndex');
        $this->mockedEs->shouldReceive('setType');
    }

    public function tearDown()
    {
        // Disable search engine and close mockery
        Config::set('search_engine.enabled', false);
        Config::set('search_engine.application_name', 'dev');
        m::close();
    }

    public function testShouldIndexObject()
    {
        $product = testProductProvider::saved('simple_valid_product');
        $attributes = $product->getAttributes();

        unset($attributes['_id']);

        // Important, the array sent to the elasticsearch should not contain the _id within the
        // first parameter
        $should_send = $product->getAttributes();
        unset($should_send['_id']);

        $this->mockedEs
            ->shouldReceive('index')
            ->with($should_send, $product->_id)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->indexObject($product);
    }

    public function testShouldSearchObject()
    {
        Config::set('search_engine.application_name', 'laramongo_test');

        // cleaning cached element
        $elements = array('products', 'contents', 'categories');

        $engine = new ElasticSearchEngine;
        $engine->connect();

        foreach ($elements as $element) {
            $engine->prepareIndexationPath($element);
            $engine->es->delete();
        }

        // creating a product
        $product = testProductProvider::saved('simple_valid_product');
        //waiting elastic Search Index.
        sleep(1);

        $engine->prepareIndexationPath(array('products', 'categories', 'contents'));
        $engine->searchObject();

        $result = $engine->getResultBy('products');

        Config::set('search_engine.application_name', 'dev');

        $this->assertInstanceOf('Product', $result[0]);
    }

    public function testShouldMapCategory()
    {
        Config::set('search_engine.application_name', 'laramongo_test');

        $mapping = [
            'properties' => [
                'characteristics' => [
                    'properties' => [
                        clean_case('Capacidade') => [
                            'type' => 'multi_field',
                            'fields' => [
                                'as_integer' => ['type'=>'integer']
                            ]
                        ],
                        clean_case('Quantidade') => [
                            'type' => 'multi_field',
                            'fields' => [
                                'as_float' => ['type'=>'float']
                            ]
                        ],
                        clean_case('Coleção') => [
                            'type' => 'multi_field',
                            'fields' => [
                                'as_string' => ['type'=>'string', 'index' => 'not_analyzed']
                            ]
                        ],
                        clean_case('Cor') => [
                            'type' => 'multi_field',
                            'fields' => [
                                'as_string' => ['type'=>'string', 'index' => 'not_analyzed']
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $category = testCategoryProvider::instance('leaf_with_facets');

        $this->mockedEs
            ->shouldReceive('map')
            ->with($mapping)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->mapCategory($category);
    }

    public function testShouldDoFacetSearch()
    {
        Config::set('search_engine.application_name', 'laramongo_test');

        $category = testCategoryProvider::instance('leaf_with_facets');

        $query = [
            'query' => [
                'filtered' => [
                    'query' => [
                        'term' => [
                            'category' => (string)$category->_id
                        ]
                    ]
                ]
            ],

            'facets' => [
                'Capacidade' => [
                    'histogram' => [
                        'field' => 'characteristics.capacidade.as_integer',
                        'interval' => 10
                    ]
                ],

                'Cor' => [
                    'terms' => [
                        'field' => 'characteristics.cor.as_string'
                    ]
                ],

                'Coleção' => [
                    'terms' => [
                        'field' => 'characteristics.colecao.as_string'
                    ]
                ],

                'Quantidade' => [
                    'histogram' => [
                        'field' => 'characteristics.quantidade.as_float',
                        'interval' => 10
                    ]
                ]
            ]
        ];

        $this->mockedEs
            ->shouldReceive('search')
            ->with($query)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->facetSearch($category);
    }

    public function testShouldFilterFacetSearch()
    {
        Config::set('search_engine.application_name', 'laramongo_test');

        $category = testCategoryProvider::instance('leaf_with_facets');

        $filter = [
            clean_case('Capacidade') => 10,
            clean_case('Colecao') => 'Alguma'
        ];

        $query = [
            'query' => [
                'filtered' => [
                    'query' => [
                        'term' => [
                            'category' => (string)$category->_id
                        ]
                    ],
                    'filter' => [
                        'and' => [
                            ['range' => [
                                'characteristics.capacidade.as_integer' => [
                                    'from'=>10, 'to'=>10+10
                                ],
                            ]],
                            ['term' => [
                                'characteristics.colecao.as_string' => 'Alguma'
                            ]]                         
                        ]
                    ]
                ]
            ],
            'facets' => [
                'Capacidade' => [
                    'histogram' => [
                        'field' => 'characteristics.capacidade.as_integer',
                        'interval' => 10
                    ]

                ],
                'Cor' => [
                    'terms' => [
                        'field' => 'characteristics.cor.as_string'
                    ]

                ],
                'Coleção' => [
                    'terms' => [
                        'field' => 'characteristics.colecao.as_string'
                    ]
                ],
                'Quantidade' => [
                    'histogram' => [
                        'field' => 'characteristics.quantidade.as_float',
                        'interval' => 10
                    ]

                ]

            ]
        ];

        $this->mockedEs
            ->shouldReceive('search')
            ->with($query)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->facetSearch($category, $filter);
    }

    public function testShouldGetRawResult()
    {
        Config::set('search_engine.application_name', 'laramongo_test');

        $result = [
            'facets' => [
                'terms'=> ['field'=>'Color']
            ],
            'hits' => [
                'anything' => 'lalala'
            ]
        ];

        $this->mockedEs
            ->shouldReceive('search')
            ->andReturn($result)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->searchObject();
        $this->assertEquals($result, $searchEngine->getRawResult());
    }

    public function testShouldGetFacetResult()
    {
        Config::set('search_engine.application_name', 'laramongo_test');

        $result = [
            'facets' => [
                'terms'=> ['field'=>'Color']
            ],
            'hits' => [
                'anything' => 'lalala'
            ]
        ];

        $this->mockedEs
            ->shouldReceive('search')
            ->andReturn($result)
            ->once();

        $searchEngine = new ElasticSearchEngine;
        $searchEngine->es = $this->mockedEs;

        $searchEngine->searchObject();
        $this->assertEquals($result['facets'], $searchEngine->getFacetResult());
    }
}
