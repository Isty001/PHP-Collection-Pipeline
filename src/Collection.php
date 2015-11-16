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

    /**
     * @var int
     */
    private $end;

    /**
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->end = count($items);
    }

    public function next()
    {
        if($this->currentKey++ == $this->end){
            $this->finished = true;
            return;
        }
    }

    /**
     * @param object $item
     */
    public function remove($item)
    {
        unset($this->items[array_search($item, $this->items)]);
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * @return object
     */
    public function getCurrent()
    {
        if(array_key_exists($this->currentKey, $this->items)){
            return $this->items[$this->currentKey];
        }
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * @param array $item
     */
    public function setItems(array $item)
    {
        $this->items = $item;
    }

    /**
     * @param object $item
     */
    public function addItem($item)
    {
        if(!in_array($item, $this->items)){
            $this->items[] = $item;
        }
    }
}
