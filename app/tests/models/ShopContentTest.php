<?php

use Mockery as m;

class ShopContentTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'products' );
        $this->cleanCollection( 'categories' );
        $this->cleanCollection( 'contents' );
        $this->cleanCollection( 'tags' );
    }

    /**
     * Mockery close
     */
    public function tearDown()
    {
        m::close();
    }
}
