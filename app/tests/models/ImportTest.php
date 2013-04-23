<?php

use Zizaco\FactoryMuff\Facade\FactoryMuff as f;
use Mockery as m;

class ImportTest extends TestCase
{
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

    public function testShouldHaveImportKind()
    {
        $import = new Import;
        $this->assertEquals('import', $import->kind);
    }

    public function testShouldProcess()
    {
        $filename = 'path/to/file.xlsx';

        $mockedExcelIo = m::mock();
        $mockedExcelIo
            ->shouldReceive('importFile')
            ->with($filename)
            ->andReturn(true);

        $import = new Import;
        $import->name = 'An importation';
        $import->filename = $filename;
        $import->excelIo = $mockedExcelIo; // The mock previously created

        $import->process();
        $this->assertTrue($import->isDone());
    }
}
