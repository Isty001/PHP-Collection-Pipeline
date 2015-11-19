<?php

namespace Pipeline;

class Pipeline extends PipelineProcessor
{
    /**
     * @param string $stringExpression
     * @return $this
     */
    public function filter($stringExpression)
    {
        $expression = new FilterExpression($stringExpression);
        $collection = $this->collections[$expression->getCollectionName()];

        $closure = function () use ($expression, $collection) {
            if (is_object($item = $collection->getCurrent())) {
                if (!$this->compareExpression($item, $expression)) {
                    $collection->remove($item);
                }
            }
        };
        $this->loopedClosures[] = $closure;

        return $this;
    }

    /**
     * @param string $collectionName
     * @param callable $callback
     * @return $this
     */
    public function filterCallback($collectionName, callable $callback)
    {
        $collection = $this->collections[$collectionName];

        $closure = function () use ($callback, $collection) {
            if (is_object($item = $collection->getCurrent())) {
                $result = call_user_func($callback, $item);
                if (!$result) {
                    $collection->remove($item);
                }
            }
        };
        $this->loopedClosures[] = $closure;

        return $this;
    }

    /**
     * @param string $stringExpression
     * @param string $newCollectionName
     * @return $this
     */
    public function select($stringExpression, $newCollectionName)
    {
        $expression = new FilterExpression($stringExpression);
        $checkedCollection = $this->collections[$expression->getCollectionName()];

        $newCollection = new Collection();
        $this->collections[$newCollectionName] = $newCollection;

        $closure = function () use ($expression, $checkedCollection, $newCollection) {
            if (is_object($item = $checkedCollection->getCurrent())) {
                if ($this->compareExpression($item, $expression)) {
                    $newCollection->addItem($item);
                    $newCollection->setFinished(false);
                }
            }
            if($checkedCollection->isFinished()) {
                $newCollection->setFinished(true);
            }
        };

        $this->loopedClosures[] = $closure;

        return $this;
    }

    /**
     * @param string $firstName
     * @param string $secondName
     * @param string $newName
     * @return $this
     */
    public function union($firstName, $secondName, $newName)
    {
        $first = $this->collections[$firstName];
        $second = $this->collections[$secondName];

        $newCollection = new Collection();
        $this->collections[$newName] = $newCollection;

        $closure = function () use ($first, $second, $newName, $newCollection) {
            !is_object($item = $first->getCurrent()) ?: $newCollection->addItem($item);
            !is_object($item = $second->getCurrent()) ?: $newCollection->addItem($item);
        };
        $this->loopedClosures[] = $closure;

        return $this;
    }

    /**
     * @param string $fromName
     * @param string $removedName
     * @return $this
     */
    public function sub($fromName, $removedName)
    {
        $from = $this->collections[$fromName];
        $remove = $this->collections[$removedName];

        $closure = function () use ($from, $remove) {

            if ($from->isItemSet($item = $remove->getCurrent())) {
                $from->remove($item);
            }
        };
        $this->loopedClosures[] = $closure;

        return $this;
    }

    /**
     * @param string $expression
     * @param string $order
     * @return $this
     */
    public function sort($expression, $order = self::DESC)
    {
        list($collectionName, $property) = explode('.', $expression);
        $collection = $this->collections[$collectionName];

        $closure = function () use ($property, $order, $collection) {
            $items = $collection->getItems();
            usort($items, function ($a, $b) use ($property, $order) {
                if ($this->getValue($a, $property) == $this->getValue($b, $property)) {
                    return 0;
                }
                if ($order == self::DESC) {
                    return $this->compare($a, $property, '<', $b) ? -1 : 1;
                } elseif ($order == self::ASC) {
                    return $this->compare($a, $property, '>', $b) ? -1 : 1;
                }
            });
            $collection->setItems($items);
        };
        $this->finalClosures[] = $closure;

        return $this;
    }

    /**
     * @param string $collectionName
     * @return $this
     */
    public function unique($collectionName)
    {
        $collection = $this->collections[$collectionName];

        $closure = function () use ($collection) {
            $collection->setItems(array_unique($collection->getItems(), SORT_REGULAR));
        };
        $this->finalClosures[] = $closure;

        return $this;
    }

    /**
     * @param string $collectionName
     * @param int $count
     * @return array
     */
    public function take($collectionName, $count = null)
    {
        if (!$this->processed) {
            $this->process();
        }
        $collectionItems = $this->collections[$collectionName]->getItems();
        if (!is_null($count)) {
            $collectionItems = array_slice($collectionItems, 0, $count);
        }
        return $collectionItems;
    }
}
