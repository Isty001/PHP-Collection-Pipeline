<?php

namespace Pipeline;

class Collection implements \Iterator
{
    /**
     * @var array
     */
    private $container;

    /**
     * @var int
     */
    private $offset = 0;

    public function __construct(array $input)
    {
        $this->offset = 0;
        $this->container = is_array($input[0]) ? $input[0] : $input;
    }

    /**
     * @param object $object
     */
    public function remove($object)
    {
        unset($this->container[array_search($object, $this->container)]);
    }

    /**
     * @param int $offset
     * @param int $length
     */
    public function slice(int $offset, int $length)
    {
        $this->container = array_slice($this->container, $offset, $length);
    }

    /**
     * @return array
     */
    public function getContainer() : array
    {
        return $this->container;
    }

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return $this->container[$this->offset];
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        ++$this->offset;
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return isset($this->container[$this->offset]);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->container);
        $this->offset = key($this->container);
    }
}
