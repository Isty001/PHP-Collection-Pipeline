<?php

namespace Tests;

use Pipeline\Pipeline;
use Tests\Resources\Person;

class ComplexPipelineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Person[]
     */
    private $people;

    public function setUp()
    {
        $this->people = $this->generateObjects();
    }

    public function testPipeline()
    {
        $this->assertEquals($this->normalProcess(), $this->pipelineProcess());
    }

    private function pipelineProcess()
    {
        $pipeline =
            (new Pipeline(['People' => $this->people]))
                ->filter('People.name !== John')
                ->select('People.name == Fox', 'Fox')
                ->filter('Fox.age < 30')
                ->select('People.name == Scully', 'Scully')
                ->filter('Scully.age > 30')
                ->union('Fox', 'Scully', 'Agents')
                ->sort('Agents.age', Pipeline::ASC)
                ->unique('Agents');

        return $pipeline->take('Agents');
    }

    private function normalProcess()
    {
        foreach ($this->people as $person) {
            if ($person->getName() !== 'John') {
                $authors[] = $person;
                if ($person->getName() == 'Fox' && $person->getAge() < 30) {
                    $fox[] = $person;
                } elseif ($person->getName() == 'Scully' && $person->getAge() > 30) {
                    $scully[] = $person;
                }
            }
        }
        $agents = array_merge($fox, $scully);

        usort($agents, function(Person $a, Person $b){
            if($a->getAge() == $b->getAge()){
                return 0;
            }
            return $a->getAge() > $b->getAge() ? -1 : 1;
        });
        $agents = array_unique($agents, SORT_REGULAR);

        return $agents;
    }

    /**
     * @return array
     */
    private function generateObjects()
    {
        $names = ['John', 'Smith', 'Fox', 'Scully'];
        for ($i = 0; $i <= 30; $i++) {
            $author = new Person();
            $author->setAge(rand(18, 70));
            $author->setName($names[array_rand($names)]);
            $result[] = $author;
        }
        return $result;
    }
}
