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

    public function select(string $expression) : self
    {

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
        if (!is_null($count)) {
            return array_slice($this->collections[$collectionName]->getItems(), 0, $count);
        }
        return $this->collections[$collectionName];
    }

    private function process()
    {
        Logger::add('ada');
        while (true) {
            foreach ($this->pipelineMethods as $key => $closure) {
                call_user_func($closure);
            }
            $result = null;
            foreach ($this->collections as $collection) {
                $result[] = $collection->isFinished();
                $collection->next();
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
