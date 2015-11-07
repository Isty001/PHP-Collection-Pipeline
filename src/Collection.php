<?php

namespace Pipeline;

class Collection
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var bool
     */
    private $finished = false;

    /**
     * @var int
     */
    private $currentKey = 0;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return object
     */
    public function getCurrent()
    {
        return !array_key_exists($this->currentKey, $this->items) ?: $this->items[$this->currentKey];
    }

    public function next()
    {
        $this->currentKey++;
    }

    /**
     * @return array
     */
    public function getItems() : array
    {
        return $this->items;
    }

    public function remove($item)
    {
        unset($this->items[array_search($item, $this->items)]);
    }

    /**
     * @return boolean
     */
    public function isFinished()
    {
        return $this->currentKey == array_search(end($this->items), $this->items);
    }

    /**
     * @param boolean $finished
     */
    public function setFinished(bool $finished)
    {
        $this->finished = $finished;
    }
}
