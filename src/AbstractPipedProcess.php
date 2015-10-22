<?php

namespace Pipeline;

abstract class AbstractPipedProcess
{
    /**
     * @var mixed
     */
    private $pipedData;

    /**
     * @return mixed
     */
    abstract protected function process();

    /**
     * @param mixed $pipedData
     */
    protected function setPipedData($pipedData)
    {
        $this->pipedData = $pipedData;
    }
}
