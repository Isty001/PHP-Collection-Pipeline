<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var array
     */
    private $collections;

    /**
     * @var \Closure[]
     */
    private $pipelineMethods;

    /**
     * @var string
     */
    private $inputName;

    /**
     * @param string $name
     * @param array $collectionItems
     */
    public function __construct($name, array $collectionItems)
    {
        $this->inputName = $name;
        $this->collections[$name] = $collectionItems;
    }

    /**
     * @param string $expression
     * @return Pipeline
     */
    public function filter(string $expression) : self
    {
        list($operator, $comparedTo, $collectionName, $property) = $this->parseExpression($expression);

        $closure = function ($item) use ($property, $operator, $comparedTo, $collectionName) {
            if (false == $this->compare($item, $property, $operator, $comparedTo)) {
                $collection = &$this->collections[$collectionName];
                unset($collection[array_search($item, $collection)]);
            }
        };
        $this->pipelineMethods[] = $closure;

        return $this;
    }

    /**
     * @param string $expression
     * @return array
     */
    private function parseExpression(string $expression)
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
    public function take(string $collectionName, int $count) : self
    {
        $closure = function () use ($collectionName, $count) {
            $this->collections[$collectionName] = array_slice($this->collections[$collectionName], 0, $count);
        };
        $this->pipelineMethods[] = $closure;

        return $this;
    }

    /**
     * @param string $collectionName
     * @return array
     */
    public function get(string $collectionName) : array
    {
        $this->process();
        return $this->collections[$collectionName];
    }

    private function process()
    {
        for ($i = 0; $i <= count($this->collections[$this->inputName]) + 1; $i++) {
            foreach ($this->pipelineMethods as $method) {
                if (isset($this->collections[$this->inputName][$i])) {
                    call_user_func($method, $this->collections[$this->inputName][$i]);
                }
            }
        }
    }
}
