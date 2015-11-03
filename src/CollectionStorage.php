<?php

namespace Pipeline;

class CollectionStorage
{
    /**
     * @var Collection[]
     */
    private $collections;

    public function __construct(array $collections)
    {
        $this->addCollections($collections);
    }

    /**
     * @param Collection $collection
     */
    public function add(Collection $collection)
    {
        $this->collections[$collection->getName()] = $collection;
    }

    /**
     * @param Collection $collection
     */
    public function remove(Collection $collection)
    {
        unset($this->collections[$collection->getName()]);
    }

    /**
     * @param string $name
     * @param array $items
     */
    public function update(string $name, array $items)
    {
        $this->collections[$name]->setItems($items);
    }

    /**
     * @param string $name
     * @return Collection
     */
    public function get(string $name) : Collection
    {
        return $this->collections[$name];
    }

    private function addCollections(array $collections)
    {
        $collections = is_array($collections[0]) ? $collections[0] : $collections;

        /** @var Collection $collection */
        foreach ($collections as $collection) {
            $this->collections[$collection->getName()] = $collection;
        }
    }
}
