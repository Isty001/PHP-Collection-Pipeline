<?php

namespace Tests\Pipeline;

use Tests\Resources\Author;

abstract class AbstractPipelineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    protected function createAuthors() : array
    {
        $author1 = new Author();
        $author1->setName('Hello');
        $author1->setAge(26);

        $author2 = new Author();
        $author2->setName('Hi');
        $author2->setAge(34);

        $author3 = new Author();
        $author3->setName('Hi');
        $author3->setAge(19);


        $author4 = new Author();
        $author4->setName('Asd10');
        $author4->setAge(60);

        $author5 = new Author();
        $author5->setName('Hi');
        $author5->setAge(20);

        return [$author1, $author2, $author3, $author4, $author5];
    }
}
