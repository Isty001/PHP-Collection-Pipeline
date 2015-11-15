<?php

namespace Pipeline;

class FilterExpression
{
    /**
     * @var string
     */
    private $collectionName;

    /**
     * @var string
     */
    private $comparedTo;

    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $operator;

    public function __construct($expression)
    {
        $this->parseFilterExpression($expression);
    }

    /**
     * @param string $expression
     */
    private function parseFilterExpression($expression)
    {
        list($subject, $this->operator, $this->comparedTo) = explode(' ', $expression);
        list($this->collectionName, $this->property) = explode('.', $subject);
    }

    /**
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * @return string
     */
    public function getComparedTo()
    {
        return $this->comparedTo;
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }
}
