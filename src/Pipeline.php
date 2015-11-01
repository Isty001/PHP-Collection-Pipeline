<?php

namespace Pipeline;

class Pipeline
{
    /**
     * @var mixed
     */
    protected $collection;

    /**
     * @param ...$input
     */
    public function __construct(...$input)
    {
        $this->collection = new Collection(is_array($input[0]) ? $input[0] : $input);
    }

    /**
     * @param string $expression
     * @return Pipeline
     */
    public function filter(string $expression) : self
    {
        list($subject, $operator, $comparedTo) = explode(' ', $expression);

        foreach ($this->collection as $element) {
            if (method_exists($method = 'get' . ucfirst($subject), $element)) {
                $value = $method;
            } elseif (property_exists($subject, $element)) {
                $value = $subject;
            }
            if (isset($value)) {
                if (!$this->compare($element->$method(), $operator, $comparedTo)) {
                    $this->collection->remove($element);
                }
            }
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
    private function compare($subject, string $operator, $compareTo)
    {
        $compareTo = $compareTo !== 'null' ?: null;

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
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
