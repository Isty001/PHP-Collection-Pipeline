<?php
//
//namespace Tests;
//
//use Pipeline\Pipeline;
//use Tests\Resources\Author;
//
//class PipelineTest extends \PHPUnit_Framework_TestCase
//{
//    public function testFilter()
//    {
//        $authors = $this->createAuthors();
//        $result =
//            (new Pipeline(['authors' => $authors]))
//                ->filter('authors.name == Chris')
//                ->filter('authors.age > 19')
//                ->take('authors');
//
//        $this->assertEquals([1 => $authors[1], 4 => $authors[4]], $result);
//    }
//
//    public function testNegativeExpression()
//    {
//        $authors = $this->createAuthors();
//        $result =
//            (new Pipeline(['authors' => $authors]))
//                ->filter('authors.name !== Chris')
//                ->take('authors');
//
//        $this->assertEquals([0 => $authors[0], 3 => $authors[3], 5 => $authors[5]], $result);
//    }
//
//    public function testFilterCallback()
//    {
//        $expected = $this->createAuthors();
//        unset($expected[3]);
//        unset($expected[5]);
//
//        $collection =
//            (new Pipeline(['expected' => $this->createAuthors()]))
//                ->filterCallback('expected', function (Author $item) {
//                    if ($item->getName() == 'Kate' || $item->getName() == 'Smith') {
//                        return false;
//                    }
//                    return true;
//                });
//        $result = $collection->take('expected', 2);
//
//        $this->assertEquals([$expected[0], $expected[1]], $result);
//    }
//
//    public function testSort()
//    {
//        $authors = $this->createAuthors();
//        $result =
//            (new Pipeline(['authors' => $authors]))
//                ->sort('authors.age', Pipeline::ASC)
//                ->take('authors');
//
//        $this->assertEquals([$authors[3], $authors[5], $authors[1], $authors[0], $authors[4], $authors[2]], $result);
//    }
//
//    public function testUnique()
//    {
//        $authors = $this->createAuthors();
//        unset($authors[5]);
//
//        $result =
//            (new Pipeline(['authors' => $authors]))
//                ->unique('authors')
//                ->take('authors');
//
//        $this->assertEquals($authors, $result);
//    }
//
//    public function testSelect()
//    {
//        $authors = $this->createAuthors();
//        $result =
//            (new Pipeline(['authors' => $authors]))
//                ->select('authors.age > 26', 'olderAuthors')
//                ->take('olderAuthors', 2);
//
//        $this->assertEquals([0 => $authors[1], 1 => $authors[3]], $result);
//    }
//
//    public function testUnion()
//    {
//        $authors = $this->createAuthors();
//        $result =
//            (new Pipeline(['authors1' => $authors, 'authors2' => $authors]))
//                ->select('authors1.name == John', 'John')
//                ->select('authors2.age == 60', 'Old')
//                ->union('John', 'Old', 'Union')
//                ->take('Union');
//
//        $this->assertEquals([$authors[0], $authors[3], $authors[5]], $result);
//    }
//
//    public function testDiff()
//    {
//        $authors = $this->createAuthors();
//
//        $result =
//            (new Pipeline(['authors' => $authors]))
//                ->select('authors.age < 60', 'age')
//                ->sub('authors', 'age')
//                ->take('authors');
//
//        $this->assertEquals([3 => $authors[3], 5 => $authors[5]], $result);
//    }
//
//    /**
//     * @return array
//     */
//    private function createAuthors()
//    {
//        $author0 = new Author();
//        $author0->setName('John');
//        $author0->setAge(26);
//
//        $author1 = new Author();
//        $author1->setName('Chris');
//        $author1->setAge(34);
//
//        $author2 = new Author();
//        $author2->setName('Chris');
//        $author2->setAge(19);
//
//        $author3 = new Author();
//        $author3->setName('Smith');
//        $author3->setAge(60);
//
//        $author4 = new Author();
//        $author4->setName('Chris');
//        $author4->setAge(20);
//
//        $author5 = new Author();
//        $author5->setName('Kate');
//        $author5->setAge(60);
//
//        return [$author0, $author1, $author2, $author3, $author4, $author5];
//    }
//}
