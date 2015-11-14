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
    private $closures;

    /**
     * @var bool
     */
    private $processed;

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
    public function filter($expression)
    {
        list($operator, $comparedTo, $collectionName, $property) = $this->parseFilterExpression($expression);

        $collection = $this->collections[$collectionName];
        $closure = function () use ($property, $operator, $comparedTo, $collection) {
            if (is_object($item = $collection->getCurrent())) {
                if (!$this->compare($item, $property, $operator, $comparedTo)) {
                    $collection->remove($item);
                    return false;
                }
            }
        };
        $this->closures[] = $closure;

        return $this;
    }

    /**
     * @param string $expression
     * @return array
     */
    private function parseFilterExpression($expression)
    {
        list($subject, $operator, $comparedTo) = explode(' ', $expression);
        list($collectionName, $property) = explode('.', $subject);

        return [$operator, $comparedTo, $collectionName, $property];
    }

    /**
     * @param object $subject
     * @param string $property
     * @param string $operator
     * @param $comparedTo
     * @return bool
     */
    private function compare($subject, $property, $operator, $comparedTo)
    {
        $subjectValue = $this->getValue($subject, $property);
        $compareValue = is_object($comparedTo) ? $this->getValue($comparedTo, $property) : $comparedTo;

        switch ($operator) {
            case '==':
                return $subjectValue == $compareValue;
            case '>':
                return $subjectValue > $compareValue;
            case '<':
                return $subjectValue < $compareValue;
            case '!==':
                return $subjectValue !== $compareValue;
        }
    }

    /**
     * @param string $expression
     * @param string $order
     * @return Pipeline
     */
    public function sort($expression, $order = Options::DESC)
    {
        list($collectionName, $property) = explode('.', $expression);

        $collection = $this->collections[$collectionName];
        $items = $collection->getItems();

        usort($items, function($a, $b) use ($property, $order){
            if($this->getValue($a, $property) == $this->getValue($b, $property)){
                return 0;
            }
            if($order == Options::DESC){
                return $this->compare($a, $property, '<', $b) ? -1 : 1;
            } elseif ($order == Options::ASC){
                return $this->compare($a, $property, '>', $b) ? -1 : 1;
            }
        });
        $collection->setItems($items);

        return $this;
    }

    /**
     * @param string $item
     * @param string $property
     * @return mixed
     */
    private function getValue($item, $property)
    {
        $method = 'get' . ucfirst($property);
        if (method_exists($item, $method)) {
            $value = $item->$method();
            return $value;
        } elseif (property_exists(get_class($item), $property) && !isset($value)) {
            $value = $item->$property;
            return $value;
        }
        return $value;
    }

    /**
     * @param string $collectionName
     * @param callable $callback
     * @return Pipeline
     */
    public function filterCallback($collectionName, Callable $callback)
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
        $this->closures[] = $closure;

        return $this;
    }

    /**
     * @param string $collectionName
     * @param int $count
     * @return array
     */
    public function take($collectionName, $count = null)
    {
        empty($this->closures) ?: $this->process();
        $collectionItems = $this->collections[$collectionName]->getItems();
        if(!is_null($count)){
            array_slice($collectionItems, 0, $count);
        }
        return $collectionItems;
    }

    private function process()
    {
        if($this->processed){
            return;
        }
        while (true) {
            foreach ($this->closures as $key => $closure) {
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
