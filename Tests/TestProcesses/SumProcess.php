<?php

namespace Tests\TestProcesses;

use Pipeline\AbstractPipedProcess;
use Pipeline\PipedProcessInterface;

class SumProcess implements PipedProcessInterface
{
    /**
     * @var mixed
     */
    private $pipedData;

    /**
     * {@inheritDoc}
     */
    public function getProcessResult()
    {
        return array_sum((array)json_decode($this->pipedData));
    }

    /**
     * {@inheritDoc}
     */
    public function setPipedData($pipedData)
    {
        $this->pipedData = $pipedData;
    }
}
