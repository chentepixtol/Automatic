<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
class Transition
{

    /**
     *
     * @var StateInterface
     */
    private $currentState;

    /**
     *
     * @var ConditionInterface
     */
    private $condition;

    /**
     *
     * @var StateInterface
     */
    private $nextState;

    /**
     *
     * @param StateInterface $currentState
     * @param Condition $condition
     * @param StateInterface $nextState
     */
    public function __construct(StateInterface $currentState, ConditionInterface $condition, StateInterface $nextState){
        $this->currentState = $currentState;
        $this->condition = $condition;
        $this->nextState = $nextState;
    }

    /**
     *
     * @return string
     */
    public function getIndex(){
        return "{$this->currentState->getKey()}-{$this->condition->getKey()}-{$this->nextState->getKey()}";
    }

    /**
     *
     * @return \Automatic\StateInterface
     */
    public function getCurrentState(){
        return $this->currentState;
    }

    /**
     *
     * @return \Automatic\StateInterface
     */
    public function getNextState(){
        return $this->nextState;
    }

    /**
     *
     * @return \Automatic\ConditionInterface
     */
    public function getCondition(){
        return $this->condition;
    }

}