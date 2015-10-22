<?php

namespace Tests\TestProcesses;

use Pipeline\PipedProcessInterface;

class ArrayProcess implements PipedProcessInterface
{
    /**
     * @var array
     */
    private $array;

    /**
     * @var mixed
     */
    private $pipedData;

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * {@inheritDoc}
     */
    public function getProcessResult()
    {
        return array_flip($this->array);
    }

    /**
     * {@inheritDoc}
     */
    public function setPipedData($pipedData)
    {
        $this->pipedData = $pipedData;
    }
}
