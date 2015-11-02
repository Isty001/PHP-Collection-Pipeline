<?php

namespace Tests\Pipeline;

use Pipeline\Collection;
use Pipeline\Pipeline;

class FilterTest extends AbstractPipelineTest
{
    public function testFilter()
    {
        $this->assertEquals($this->createExpected(), $this->authorFilter());
    }

    /**
     * @return array
     */
    private function authorFilter()
    {
        $collection = new Collection('authors', $this->createAuthors());

        return (new Pipeline($collection))
            ->filter('authors.name == Hi')
            ->filter('authors.age > 30')
            ->getItemsOf('authors');
    }

    /**
     * @return array
     */
    private function createExpected()
    {
        list($author1, $author2, $author3) = $this->createAuthors();
        return [1 => $author2, 2 => $author3];
    }
}
