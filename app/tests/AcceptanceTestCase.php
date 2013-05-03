<?php

class AcceptanceTestCase extends Zizaco\TestCases\IntegrationTestCase {

    public function setUp()
    {
        parent::setUp();

        Session::set('region', 'sao_paulo');
    }

    public function tearDown()
    {
        Session::forget('region');
    }
}
