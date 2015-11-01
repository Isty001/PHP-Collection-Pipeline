<?php

namespace Tests\Pipeline;

use Pipeline\Pipeline;
use Tests\Resources\Author;

class FilterTest extends PipelineTest
{
    public function testFilter()
    {
        $expected = $this->authorSort();
        $result =  $this->pipedAuthorFilter();

        $this->assertEquals($expected, $result);
        $this->assertEquals($expected[0], $result[0]);
    }

    /**
     * @return array
     */
    private function pipedAuthorFilter()
    {
        list($author1, $author2) = $this->createAuthors();

        return (new Pipeline($author1, $author2))
            ->filter('name == Hi')
            ->filter('age < 50')
            ->take(2)
            ->getResult();
    }

    /**
     * @return array
     */
    private function authorSort()
    {
        list($author1, $author2) = $this->createAuthors();

        $authors = [$author1, $author2];

        /** @var Author $author */
        foreach ($authors as $author) {
            if ($author->getName() == 'Hi') {
                $age = $author->getAge();
                if ($age !== null) {
                    $result[] = $author;
                }
            }
        }
        return $result;
    }
}
