<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var \SplObjectStorage
     */
    private $processes;

    /**
     * @var mixed
     */
    private $result;

    public function __construct()
    {
        $this->processes = new \SplObjectStorage();
    }

    /**
     * @param AbstractPipedProcess $process
     * @return $this
     */
    public function pipe(AbstractPipedProcess $process)
    {
        $this->processes->attach($process);
        return $this;
    }

    private function process()
    {
        foreach($this->processes as $process){
            $result = $process->process();
        }
        return $result;
    }

    /**
     * @return \SplObjectStorage
     */
    public function getProcesses()
    {
        return $this->processes;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        if(empty($this->result)){
            return $this->process();
        }
        return $this->result;
    }
}
