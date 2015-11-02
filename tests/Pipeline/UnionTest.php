<?php

namespace Tests\Pipeline;

use Pipeline\{Collection, Pipeline};

class UnionTest extends AbstractPipelineTest
{
    public function testUnion()
    {
        $this->assertEquals($this->createAuthors(), $this->authorUnion());
    }

    /**
     * @return array
     */
    private function authorUnion()
    {
        $collection1 = new Collection('authors_1', $this->createAuthors());
        $collection2 = new Collection('authors_2', $this->createAuthors());

        return (new Pipeline($collection1, $collection2))
            ->filter('authors_1.name == Hello')
            ->distinct('authors_1')
            ->slice('authors_2', 1, 2)
            ->union('authors_1', 'authors_2', 'unified_authors')
            ->getItemsOf('unified_authors');
    }
}
