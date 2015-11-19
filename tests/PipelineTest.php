<?php

namespace Tests;

use Pipeline\Pipeline;
use Tests\Resources\Person;

class PipelineTest extends \PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $people = $this->createAuthors();
        $result =
            (new Pipeline(['people' => $people]))
                ->filter('people.name == Chris')
                ->filter('people.age > 19')
                ->take('people');

        $this->assertEquals([1 => $people[1], 4 => $people[4]], $result);
    }

    public function testNegativeExpression()
    {
        $people = $this->createAuthors();
        $result =
            (new Pipeline(['people' => $people]))
                ->filter('people.name !== Chris')
                ->take('people');

        $this->assertEquals([0 => $people[0], 3 => $people[3], 5 => $people[5]], $result);
    }

    public function testFilterCallback()
    {
        $expected = $this->createAuthors();
        unset($expected[3]);
        unset($expected[5]);

        $collection =
            (new Pipeline(['expected' => $this->createAuthors()]))
                ->filterCallback('expected', function (Person $item) {
                    if ($item->getName() == 'Kate' || $item->getName() == 'Smith') {
                        return false;
                    }
                    return true;
                });
        $result = $collection->take('expected', 2);

        $this->assertEquals([$expected[0], $expected[1]], $result);
    }

    public function testSort()
    {
        $people = $this->createAuthors();
        $result =
            (new Pipeline(['people' => $people]))
                ->sort('people.age', Pipeline::ASC)
                ->take('people');

        $this->assertEquals([$people[3], $people[5], $people[1], $people[0], $people[4], $people[2]], $result);
    }

    public function testUnique()
    {
        $people = $this->createAuthors();
        unset($people[5]);

        $result =
            (new Pipeline(['people' => $people]))
                ->unique('people')
                ->take('people');

        $this->assertEquals($people, $result);
    }

    public function testSelect()
    {
        $people = $this->createAuthors();
        $result =
            (new Pipeline(['people' => $people]))
                ->select('people.age > 26', 'olderPeople')
                ->take('olderPeople', 2);

        $this->assertEquals([0 => $people[1], 1 => $people[3]], $result);
    }

    public function testUnion()
    {
        $people = $this->createAuthors();
        $result =
            (new Pipeline(['people1' => $people, 'people2' => $people]))
                ->select('people1.name == John', 'John')
                ->select('people2.age == 60', 'Old')
                ->union('John', 'Old', 'Union')
                ->take('Union');

        $this->assertEquals([$people[0], $people[3], $people[5]], $result);
    }

    public function testDiff()
    {
        $people = $this->createAuthors();

        $result =
            (new Pipeline(['authors' => $people]))
                ->select('authors.age < 60', 'age')
                ->sub('authors', 'age')
                ->take('authors');

        $this->assertEquals([3 => $people[3], 5 => $people[5]], $result);
    }

    /**
     * @return array
     */
    private function createAuthors()
    {
        $person0 = new Person();
        $person0->setName('John');
        $person0->setAge(26);

        $person1 = new Person();
        $person1->setName('Chris');
        $person1->setAge(34);

        $person2 = new Person();
        $person2->setName('Chris');
        $person2->setAge(19);

        $person3 = new Person();
        $person3->setName('Smith');
        $person3->setAge(60);

        $person4 = new Person();
        $person4->setName('Chris');
        $person4->setAge(20);

        $person5 = new Person();
        $person5->setName('Kate');
        $person5->setAge(60);

        return [$person0, $person1, $person2, $person3, $person4, $person5];
    }
}
