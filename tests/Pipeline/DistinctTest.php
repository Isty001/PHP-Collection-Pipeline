<?php

namespace Tests\Pipeline;

use Pipeline\Collection;
use Pipeline\Pipeline;

class DistinctTest extends AbstractPipelineTest
{
    public function testDistinct()
    {
        $this->assertEquals($this->createExpected(), $this->authorDistinct());
    }

    /**
     * @return array
     */
    private function authorDistinct()
    {
        $collection = new Collection('authors', $this->createAuthors());

        return (new Pipeline($collection))
            ->distinct('authors')
            ->getItemsOf('authors');
    }

    /**
     * @return array
     */
    private function createExpected()
    {
        list($author1, $author2) = $this->createAuthors();
        return [$author1, $author2];
    }
}
