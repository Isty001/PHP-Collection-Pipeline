<?php

namespace Tests\Pipeline;

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
    private function authorFilter() : array
    {
        return (new Pipeline('authors', $this->createAuthors()))
            ->filter('authors.name == Hi')
            ->filter('authors.age > 19')
            ->select('young', 'authors.age < 19')
            ->take('authors', 1)
            ->get('authors');
    }

    /**
     * @return array
     */
    private function createExpected() : array
    {
        list($author1, $author2, $author3, $author4, $author5) = $this->createAuthors();
        return [0 => $author2];
    }
}
