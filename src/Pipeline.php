<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var \SplObjectStorage
     */
    private $processes;

    public function __construct()
    {
        $this->processes = new \SplObjectStorage();
    }

    /**
     * @param PipedProcessInterface $process
     * @return Pipeline $this
     */
    public function pipe(PipedProcessInterface $process)
    {
        $this->processes->attach($process);
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function process()
    {
        $result = null;
        /** @var PipedProcessInterface $process */
        foreach($this->processes as $process){
            $process->setPipedData($result);
            $result = $process->getProcessResult();
        }
        return isset($result) ? $result : null;
    }
}
