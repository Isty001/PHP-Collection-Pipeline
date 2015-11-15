<?php

namespace Pipeline;

class PipelineProcessor
{
    const DESC = 'DESC';
    const ASC = 'ASC';

    /**
     * @var Collection[]
     */
    protected $collections;

    /**
     * @var \Closure[]
     */
    protected $loopedClosures = [];

    /**
     * @var \Closure[]
     */
    protected $finalClosures = [];

    /**
     * @var bool
     */
    protected $processed;

    /**
     * @param array $collections
     */
    public function __construct(array $collections)
    {
        $this->addCollections($collections);
    }

    /**
     * @param array $collections
     */
    protected function addCollections(array $collections)
    {
        foreach ($collections as $name => $items) {
            $this->collections[$name] = new Collection($items);
        }
    }

    /**
     * @param object $subject
     * @param string $property
     * @param string $operator
     * @param string $comparedTo
     * @return bool
     */
    protected function compare($subject, $property, $operator, $comparedTo)
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
     * @param object $item
     * @param FilterExpression $expression
     * @return bool
     */
    protected function compareExpression($item, FilterExpression $expression)
    {
        return $this->compare($item, $expression->getProperty(), $expression->getOperator(), $expression->getComparedTo());
    }

    /**
     * @param string $item
     * @param string $property
     * @return mixed
     */
    protected function getValue($item, $property)
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
     * Data Processing
     */
    protected function process()
    {
        while (true) {
            foreach ($this->loopedClosures as $loopedClosure) {
                call_user_func($loopedClosure);
            }
            $result = null;
            foreach ($this->collections as $key => $collection) {
                if (!$result[] = $collection->isFinished()) {
                    $collection->next();
                }
            }
            if (count(array_unique($result)) == 1 && $result[0]) {
                break;
            }
        }
        foreach ($this->finalClosures as $finalClosure) {
            call_user_func($finalClosure);
        }
        $this->processed = true;
    }
}
