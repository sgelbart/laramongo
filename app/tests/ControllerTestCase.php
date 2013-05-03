<?php

class ControllerTestCase extends Zizaco\TestCases\ControllerTestCase {

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
