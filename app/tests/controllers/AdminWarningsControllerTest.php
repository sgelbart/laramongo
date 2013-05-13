<?php

class AdminWarningsControllerTest extends ControllerTestCase
{
    use TestHelper;

    public function setUp()
    {
        parent::setUp();

        $this->cleanCollection( 'warnings' );
    }

    public function testShouldGetListOfSynonymous()
    {
        $this->requestAction('GET', 'Admin\WarningsController@index');
        $this->assertRequestOk();
    }
}
