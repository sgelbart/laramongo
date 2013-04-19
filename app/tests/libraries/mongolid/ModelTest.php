<?php

use Zizaco\Mongolid\Model;
use Mockery as m;

class ModelTest extends PHPUnit_Framework_TestCase
{
    protected $mongoMock = null;
    protected $productsCollection = null;
    protected $categoriesCollection = null;
    protected $cursor = null;

    public function setUp()
    {
        $this->mongoMock = m::mock('Connection');
        $this->productsCollection = m::mock('Collection');
        $this->categoriesCollection = m::mock('Collection');
        $this->cursor = m::mock(new _stubCursor);

        $this->mongoMock->mongolid = $this->mongoMock;
        $this->mongoMock->test_products = $this->productsCollection;
        $this->mongoMock->test_categories = $this->categoriesCollection;

        _stubProduct::$connection = $this->mongoMock;
        _stubCategories::$connection = $this->mongoMock;
    }

    public function tearDown()
    {
        m::close();
    }

    public function testShouldSave()
    {
        $prod = new _stubProduct;
        $prod->name = 'Something';

        $this->productsCollection
            ->shouldReceive('save')
            ->with(
                $this->prepareMongoAttributes( $prod->attributes ),
                ['w'=>1]
            )
            ->once()
            ->andReturn(['ok'=>1]);

        $this->assertTrue($prod->save());
    }

    public function testShouldDelete()
    {
        $prod = new _stubProduct;
        $prod->name = 'Something';

        $this->productsCollection
            ->shouldReceive('remove')
            ->with(
                $this->prepareMongoAttributes( $prod->attributes )
            )
            ->once()
            ->andReturn(['ok'=>1]);

        $this->assertTrue($prod->delete());
    }

    public function testShouldFindFirst()
    {
        $existentProduct = [
            '_id'=>new MongoId,
            'name'=>'Bacon',
            'price'=>10.50,
        ];

        $query = ['name'=>'Bacon'];

        $this->productsCollection
            ->shouldReceive('findOne')
            ->with(
                $query ,[]
            )
            ->once()
            ->andReturn(
                $existentProduct
            );

        $result = _stubProduct::first($query);
        $this->assertEquals($existentProduct, $result->toArray());

        // With fields parameter
        unset($existentProduct['name']);

        $this->productsCollection
            ->shouldReceive('findOne')
            ->with(
                $query ,['price'=>1]
            )
            ->once()
            ->andReturn(
                $existentProduct
            );

        $result = _stubProduct::first($query,['price']);
        $this->assertEquals($existentProduct, $result->toArray());
    }

    public function testShouldFind()
    {
        $existentProduct = [
            '_id'=>new MongoId,
            'name'=>'Bacon',
            'price'=>10.50,
        ];

        $query = ['name'=>'Bacon'];

        $fields = ['name','price'];

        $this->productsCollection
            ->shouldReceive('find')
            ->with(
                $query ,['name'=>1,'price'=>1]
            )
            ->once()
            ->andReturn(
                $this->cursor
            );

        $this->cursor
            ->shouldReceive('count')
            ->once()
            ->andReturn(1);
        $this->cursor
            ->shouldReceive('rewind')
            ->once()
            ->andReturn($this->cursor);
        $this->cursor
            ->shouldReceive('current')
            ->once()
            ->andReturn($existentProduct);


        $result = _stubProduct::find($query, $fields);
        $this->assertEquals($existentProduct, $result->toArray());
    }

    public function testShouldWhere()
    {
        $existentProduct = [
            '_id'=>new MongoId,
            'name'=>'Bacon',
            'price'=>10.50,
        ];

        $query = ['name'=>'Bacon'];

        $fields = ['name','price'];

        $this->productsCollection
            ->shouldReceive('find')
            ->with(
                $query , []
            )
            ->once()
            ->andReturn(
                $this->cursor
            );

        $this->cursor
            ->shouldReceive('rewind')
            ->once()
            ->andReturn($this->cursor);

        $result = _stubProduct::where($query, $fields, true);
        $this->assertInstanceOf('Zizaco\Mongolid\CachableOdmCursor', $result);
    }

    public function testShouldWhereAsCachable()
    {
        $existentProduct = [
            '_id'=>new MongoId,
            'name'=>'Bacon',
            'price'=>10.50,
        ];

        $query = ['name'=>'Bacon'];

        $fields = ['name','price'];

        $this->productsCollection
            ->shouldReceive('find')
            ->with(
                $query ,['name'=>1,'price'=>1]
            )
            ->once()
            ->andReturn(
                $this->cursor
            );

        $result = _stubProduct::where($query, $fields);
        $this->assertInstanceOf('Zizaco\Mongolid\OdmCursor', $result);
    }

    public function testShouldParseDocument()
    {
        $document = [
            '_id'=>new MongoId,
            'name'=>'Bacon',
            'price'=>10.50,
        ];

        $prod = new _stubProduct;
        $prod->parseDocument( $document );

        $this->assertEquals($document, $prod->attributes);
    }

    /**
     * Prepare attributes to be used in MongoDb.
     * especially the _id.
     *
     * @param array $attr
     * @return array
     */
    private function prepareMongoAttributes($attr)
    {
        // Translate the primary key field into _id
        if( isset($attr['_id']) )
        {
            // If its a 24 digits hexadecimal, then it's a MongoId
            if ($this->isMongoId($attr['_id']))
            {
                $attr['_id'] = new \MongoId( $attr['_id'] );   
            }
            elseif(is_numeric($attr['_id']))
            {
                $attr['_id'] = (int)$attr['_id'];
            }
            else{
                $attr['_id'] = $attr['_id'];   
            }
        }

        return $attr;
    }
}

class _stubProduct extends Model {
    protected $collection = 'test_products';
}

class _stubCategories extends Model {
    protected $collection = 'test_categories';
}

class _stubCursor {

    public $validCount = 1;

    public function valid()
    {
        $this->validCount--;
        return $this->validCount > 0;
    }
}

