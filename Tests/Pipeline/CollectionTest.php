<?php

namespace Tests\Pipeline;

use Pipeline\Collection;
use Tests\Resources\Author;

class CollectionTest extends PipelineTest
{
    public function testCollection()
    {
        $collection = new Collection($this->createAuthors());

        foreach ($collection as $element) {
            $this->assertInstanceOf(Author::class, $element);
        }

        $collection->slice(0, 1);
        $this->assertEquals(1, count($collection->getContainer()));
    }
}
