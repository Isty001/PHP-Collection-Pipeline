<?php

namespace Tests\Pipeline;

use Tests\Resources\Author;

class PipelineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    protected function createAuthors()
    {
        $author1 = new Author();
        $author1->setName('Hello');
        $author1->setAge(26);

        $author2 = new Author();
        $author2->setName('Hi');
        $author2->setAge(34);

        return [$author1, $author2];
    }
}
