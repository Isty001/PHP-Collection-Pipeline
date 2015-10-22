<?php

namespace Tests;

use Pipeline\Pipeline;
use Tests\TestProcesses\ArrayProcess;
use Tests\TestProcesses\SumProcess;
use Tests\TestProcesses\JsonProcess;

class PipelineTest extends \PHPUnit_Framework_TestCase
{
    public function testPipeline()
    {
        $pipeline = new Pipeline();

        $pipeline->pipe(new ArrayProcess([1 => 'One', 2 => 'Two', 3 => 'Three']));
        $this->assertEquals($flippedArray = ['One' => 1, 'Two' => 2, 'Three' => 3], $pipeline->process());

        $pipeline->pipe(new JsonProcess());
        $this->assertEquals(json_encode($flippedArray), $pipeline->process());

        $pipeline->pipe(new SumProcess());
        $this->assertEquals(6, $pipeline->process());
    }
}
