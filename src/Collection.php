<?php

namespace Pipeline;

class Collection
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $items;

    public function __construct(string $name, ...$items)
    {
        $this->name = $name;
        $this->items = is_array($items[0]) ? $items[0] : $items;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    public function getItems() : array
    {
        return $this->items;
    }
}
