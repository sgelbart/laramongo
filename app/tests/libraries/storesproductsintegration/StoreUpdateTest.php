<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;
use Mockery as m;
use Laramongo\StoresProductsIntegration\StoreUpdate;

class StoreUpdateTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;
    
    /**
     * Clean delayedTasks collection
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'delayedTasks' );

        Config::set('queue.default','sync');
    }

    /**
     * Mockery close
     */
    public function tearDown()
    {
        m::close();
    }

    public function testShouldHaveStoreUpdateKind()
    {
        $storeUpdate = new StoreUpdate;
        $this->assertEquals('storeUpdate', $storeUpdate->type);
    }

    public function testShouldProcess()
    {
        $filename = '/tests/assets/partial-price-file.txt.gz';
        $extractedFilename = str_replace('.gz', '', $filename);

        $mockedCsvParser = m::mock();
        $mockedCsvParser
            ->shouldReceive('parseFile')
            ->with($extractedFilename)
            ->once()
            ->andReturn(true);

        $storeUpdate = new StoreUpdate;
        $storeUpdate->name = 'An storeUpdate';
        $storeUpdate->filename = $filename;
        $storeUpdate->parser = $mockedCsvParser; // The mock previously created

        $storeUpdate->process();
        $this->assertTrue($storeUpdate->isDone());
    }
}
