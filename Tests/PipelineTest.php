<?php

namespace Tests;

use Pipeline\AbstractPipedProcess;
use Pipeline\Pipeline;
use Tests\TestProcesses\ArrayProcess;
use Tests\TestProcesses\EchoProcess;
use Tests\TestProcesses\JsonProcess;

class PipelineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new Pipeline
     */
    public function testPipeline()
    {
        $pipeline = $this->pipeArrayProcess();

        foreach ($pipeline->getProcesses() as $process) {
            $this->assertTrue($process instanceof AbstractPipedProcess);
        }

        $expected = ['One' => 1, 'Two' => 2, 'Three' => 3];
        $this->assertEquals($pipeline->getResult(), $expected);
    }

    /**
     * @return Pipeline
     */
    private function pipeArrayProcess()
    {
        $pipeline = new Pipeline();
        $array = [1 => 'One', 2 => 'Two', 3 => 'Three'];
        $pipeline->pipe(new ArrayProcess($array));

        return $pipeline;
    }
}
