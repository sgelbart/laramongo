<?php

class WarningTest extends Zizaco\TestCases\TestCase
{
    use TestHelper;

    /**
     * Clean collection between every test
     */
    public function setUp()
    {
        parent::setUp();
        $this->cleanCollection( 'warnings' );
    }

    public function testShouldSaveWarning()
    {
        $warning = testWarningProvider::instance('warning');

        $this->assertTrue($warning->save());
    }

    public function testShouldCreateWarning()
    {
        Warning::setWarning('Example');

        $warn = Warning::first();

        $this->assertEquals("Example", $warn->keyword);
        $this->assertEquals(count(Warning::all()), 1);
    }
}
