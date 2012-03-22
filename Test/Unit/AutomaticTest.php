<?php

namespace Test\Unit;


/**
 *
 * @author chente
 *
 */
use Automatic\TransitionCollection;

use Test\Mock\Item;

use Automatic\Condition;
use Automatic\State;
use Automatic\Transition;
use Automatic\Machine;

class AutomaticTest extends BaseTest
{
    const STATE_ORIGIN = 1;
    const STATE_NEW = 2;
    const STATE_ASSIGNED = 3;
    const CONDITION_CREATE = 1;
    const CONDITION_ASSIGN = 2;

    /**
     * @test
     */
    public function getNextStates(){
        $machine = $this->getMockupMachine();
        $this->assertEquals(array(self::STATE_NEW => "New"), $machine->getNextStates($this->getOriginItem()));
    }

    /**
     * @test
     */
    public function getNextStateKeys(){
        $machine = $this->getMockupMachine();
        $this->assertEquals(array(self::STATE_NEW), $machine->getNextStateKeys($this->getOriginItem()));
    }

    /**
     * @test
     */
    public function isValidNextState(){
        $machine = $this->getMockupMachine();
        $this->assertTrue($machine->isValidNextState($this->getOriginItem(), self::STATE_NEW));
        $this->assertFalse($machine->isValidNextState($this->getOriginItem(), self::STATE_ORIGIN));
    }

    /**
     * @test
     */
    public function getValidConditions(){
        $machine = $this->getMockupMachine();
        $this->assertEquals(array(self::CONDITION_CREATE => "Create"), $machine->getValidConditions($this->getOriginItem()));
    }

    /**
     * @test
     */
    public function getValidConditionKeys(){
        $machine = $this->getMockupMachine();
        $this->assertEquals(array(self::CONDITION_CREATE), $machine->getValidConditionKeys($this->getOriginItem()));
    }

    /**
     * @test
     */
    public function isValidCondition(){
        $machine = $this->getMockupMachine();
        $this->assertTrue($machine->isValidCondition($this->getOriginItem(), self::CONDITION_CREATE));
        $this->assertFalse($machine->isValidCondition($this->getOriginItem(), self::CONDITION_ASSIGN));
    }

    /**
     * @return Machine
     */
    private function getMockupMachine(){
        return new Machine($this->getTransitions());
    }


    /**
     * @return TransitionCollection
     */
    private function getTransitions()
    {
        $collection = new TransitionCollection();

        $collection->append(
            new Transition($this->getOriginState(), new Condition(self::CONDITION_CREATE, "Create"), $this->getNewState())
        );

        return $collection;
    }

    /**
     * @return Item
     */
    private function getOriginItem(){
        return new Item(self::STATE_ORIGIN);
    }

    /**
     * @return State
     */
    private function getOriginState(){
        return new State(self::STATE_ORIGIN, "Origin");
    }

    /**
     * @return State
     */
    private function getNewState(){
        return new State(self::STATE_NEW, "New");
    }
}

