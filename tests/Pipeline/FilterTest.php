<?php

namespace Tests\Pipeline;

use Pipeline\Pipeline;
use Tests\Resources\Author;

class FilterTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @return array
     */
    private function createAuthors() : array
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
