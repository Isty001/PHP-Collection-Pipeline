<?php

namespace Tests\TestProcesses;

use Pipeline\AbstractPipedProcess;

class ArrayProcess extends AbstractPipedProcess
{
    /**
     * @var array
     */
    private $array;

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
    public function process()
    {
        return array_flip($this->array);
    }
}
