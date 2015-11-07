<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var Collection[]
     */
    private $collections;

    /**
     * @var \Closure[]
     */
    private $pipelineMethods;

    /**
     * @param array $collections
     */
    public function __construct(array $collections)
    {
        $this->addCollections($collections);
    }

    /**
     * @param string $expression
     * @return Pipeline
     */
    public function filter(string $expression) : self
    {
        list($operator, $comparedTo, $collectionName, $property) = $this->parseExpression($expression);

        $closure = function () use ($property, $operator, $comparedTo, $collectionName) {
            $collection = $this->collections[$collectionName];
            if (is_object($item = $collection->getCurrent())) {
                if (!$this->compare($item, $property, $operator, $comparedTo)) {
                    $collection->remove($item);
                    return false;
                }
            }
        };
        $this->pipelineMethods[] = $closure;

        return $this;
    }

    /**
     * @param string $collectionName
     * @param callable $callback
     * @return Pipeline
     */
    public function filterCallback(string $collectionName, Callable $callback) : self
    {
        $collection = $this->collections[$collectionName];
        $closure = function() use ($callback, $collection){
            if(is_object($item = $collection->getCurrent())){
                $result = call_user_func($callback, $item);
                if(!$result){
                    $collection->remove($item);
                }
            }
        };
        $this->pipelineMethods[] = $closure;

        return $this;
    }

    /**
     * @param string $expression
     * @return array
     */
    private function parseExpression(string $expression) : array
    {
        list($subject, $operator, $comparedTo) = explode(' ', $expression);
        list($collectionName, $property) = explode('.', $subject);

        return [$operator, $comparedTo, $collectionName, $property];
    }

    /**
     * @param object $item
     * @param string $property
     * @param string $operator
     * @param $comparedTo
     * @return bool
     */
    private function compare($item, string $property, string $operator, $comparedTo) : bool
    {
        $method = 'get' . ucfirst($property);
        if (method_exists($item, $method)) {
            $value = $item->$method();
        } elseif (property_exists(get_class($item), $property) && !isset($value)) {
            $value = $item->$property;
        }
        switch ($operator) {
            case '==':
                return $value == $comparedTo;
            case '>':
                return $value > $comparedTo;
            case '<':
                return $value < $comparedTo;
            case '!==':
                return $value !== $comparedTo;
        }
    }

    /**
     * @param string $collectionName
     * @param int $count
     * @return Pipeline
     */
    public function take(string $collectionName, int $count = null) : Collection
    {
        $this->process();
        $collection = $this->collections[$collectionName];
        if (!is_null($count)) {
            $slicedItems = array_slice($collection->getItems(), 0, $count);
            return $collection->setItems($slicedItems);
        }
        return $collection;
    }

    private function process()
    {
        while (true) {
            foreach ($this->pipelineMethods as $key => $closure) {
                call_user_func($closure);
            }
            $result = null;
            foreach ($this->collections as $collection) {
                $finished = $collection->isFinished();
                if(!$result[] = $finished){
                    $collection->next();
                }
            }
            if (count(array_unique($result)) == 1 && $result[0]) {
                break;
            }
        }
    }

    /**
     * @param array $collections
     */
    private function addCollections(array $collections)
    {
        foreach ($collections as $name => $items) {
            $this->collections[$name] = new Collection($items);
        }
    }
}
