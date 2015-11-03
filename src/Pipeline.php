<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var CollectionStorage
     */
    protected $collections;

    /**
     * @param Collection ...$input
     */
    public function __construct(Collection...$collections)
    {
        $this->collections = new CollectionStorage($collections);
    }

    /**
     * @param string $expression
     * @return Pipeline
     */
    public function filter(string $expression) : self
    {
        list($subject, $operator, $compareTo) = explode(' ', $expression);
        list($name, $property) = explode('.', $subject);

        $items = array_filter($this->collections->get($name)->getItems(),
            function ($item) use ($property, $operator, $compareTo) {
                $method = 'get' . $property;
                if (method_exists($item, $method)) {
                    return $this->compare($item->$method(), $operator, $compareTo);
                } elseif (property_exists($item, $property)) {
                    return $this->compare($item->$property, $operator, $compareTo);
                }
            });
        $this->collections->update($name, $items);

        return $this;
    }

    /**
     * @param string $name
     * @param int $offset
     * @param int $length
     * @return Pipeline
     */
    public function slice(string $name, int $offset, int $length) : self
    {
        $items = array_slice($this->collections->get($name)->getItems(), $offset, $length);
        $this->collections->update($name, $items);

        return $this;
    }

    /**
     * @param string $name
     * @return Pipeline
     */
    public function distinct(string $name) : self
    {
        $items = $this->collections->get($name)->getItems();
        $this->collections->update($name, array_unique($items, SORT_REGULAR));

        return $this;
    }

    /**
     * @param string $firstCollectionName
     * @param string $secondCollectionName
     * @param string $unifiedName
     * @return Pipeline
     */
    public function union(string $firstCollectionName, string $secondCollectionName, string $unifiedName) : self
    {
        $firstCollection = $this->collections->get($firstCollectionName);
        $secondCollection = $this->collections->get($secondCollectionName);

        $unifiedItems = array_merge($firstCollection->getItems(), $secondCollection->getItems());
        $this->collections->add(new Collection($unifiedName, $unifiedItems));

        $this->collections->remove($firstCollection);
        $this->collections->remove($secondCollection);

        return $this;
    }

    /**
     * @param mixed $subject
     * @param string $operator
     * @param mixed $compareTo
     * @return bool
     */
    private function compare($subject, string $operator, $compareTo) : bool
    {
        $compareTo !== 'null' ?: null;
        switch ($operator) {
            case '==':
                return $subject == $compareTo;
            case '>':
                return $subject > $compareTo;
            case '<':
                return $subject < $compareTo;
            case '!==':
                return $subject !== $compareTo;
        }
    }

    /**
     * @return CollectionStorage
     */
    public function getCollectionStorage() : CollectionStorage
    {
        return $this->collections;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getItemsOf(string $name)
    {
        return $this->collections->get($name)->getItems();
    }
}
