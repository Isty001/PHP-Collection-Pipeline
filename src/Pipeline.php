<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @param ...$input
     */
    public function __construct(...$input)
    {
        $this->collection = new Collection($input);
    }

    /**
     * @param string $expression
     * @return Pipeline
     */
    public function filter(string $expression)
    {
        list($subject, $operator, $compareTo) = explode(' ', $expression);
        foreach ($this->collection as $element) {
            if (method_exists($element, $method = 'get' . ucfirst($subject))) {
                $result = $this->compare($element->$method(), $operator, $compareTo);
            } elseif (property_exists($element, $subject)) {
                $result = $this->compare($element->$subject, $operator, $compareTo);
            }
            $result ?: $this->collection->remove($element);
        }
        return $this;
    }

    /**
     * @param int $length
     * @return Pipeline
     */
    public function take(int $length) : self
    {
        $this->collection->slice(0, $length);
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
     * @return array
     */
    public function getResult() : array
    {
        return $this->collection->getContainer();
    }
}
