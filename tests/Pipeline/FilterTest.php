<?php

namespace Tests\Pipeline;

use Pipeline\Pipeline;

class FilterTest extends PipelineTest
{
    public function testFilter()
    {
        $expected = $this->createExpected();
        $result = $this->pipedAuthorFilter();

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    private function pipedAuthorFilter()
    {
        return (new Pipeline($this->createAuthors()))
            ->filter('name == Hi')
            ->filter('age > 30')
            ->take(1)
            ->getResult();
    }

    /**
     * @return array
     */
    private function createExpected()
    {
        list($author1, $author2) = $this->createAuthors();
        return ["0" => $author2];
    }
}
