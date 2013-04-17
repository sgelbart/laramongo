<?php

use Laramongo\TemplateBuilder\TemplateBuilder;
use Mockery as m;

class TemplateBuilderTest extends TestCase
{

    public function testShouldMake()
    {
        $templateBuilder = new TemplateBuilder;

        $viewMock = m::mock('ViewEnvironment');
        $viewMock->shouldReceive('make')
            ->with('name.of.view', ['param'=>1])
            ->once()
            ->andReturn('Rendered view');

        App::instance('view', $viewMock);

        $result = $templateBuilder->make('name.of.view', ['param'=>1]);
        $this->assertEquals('Rendered view', $result);
    }

}
