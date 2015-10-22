<?php

namespace Pipeline;

interface PipedProcessInterface
{
    /**
     * @param mixed $pipedData
     */
    public function setPipedData($pipedData);

    /**
     * @return mixed
     */
    public function process();
}
