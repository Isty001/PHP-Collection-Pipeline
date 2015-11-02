<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var array
     */
    protected $collection;

    /**
     * @param ...$input
     */
    public function __construct(...$input)
    {
        $this->collection = is_array($input[0]) ? $input[0] : $input;
    }

    /**
     * @param string $expression
     * @return Pipeline
     */
    public function filter(string $expression) : self
    {
        list($subject, $operator, $compareTo) = explode(' ', $expression);

        $this->collection = array_filter($this->collection,
            function ($element) use ($subject, $operator, $compareTo) {
                $method = 'get' . ucfirst($subject);

                if (method_exists($element, $method)) {
                    return $this->compare($element->$method(), $operator, $compareTo);
                } elseif (property_exists($element, $subject)) {
                    return $this->compare($element->$subject, $operator, $compareTo);
                }
            });
        return $this;
    }

    /**
     * @param int $length
     * @return Pipeline
     */
    public function take(int $length) : self
    {
        $this->collection = array_slice($this->collection, 0, $length);
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
        return $this->collection;
    }
}
