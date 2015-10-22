<?php

namespace Tests\TestProcesses;

use Pipeline\PipedProcessInterface;

class JsonProcess implements PipedProcessInterface
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
        return json_encode($this->pipedData);
    }

    /**
     * {@inheritDoc}
     */
    public function setPipedData($pipedData)
    {
        $this->pipedData = $pipedData;
    }
}
