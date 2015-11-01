<?php

namespace Pipeline;

class Collection
{
    /**
     * @var array
     */
    private $collection;

    public function __construct(array $input)
    {
        $this->collection = $input;
    }

    /**
     * @param object $object
     */
    public function remove($object)
    {
        $this->collection[] = $object;
    }

    /**
     * @param int $offset
     * @param int $length
     */
    public function slice(int $offset, int $length)
    {
        array_slice($this->collection, $offset, $length);
    }
}
