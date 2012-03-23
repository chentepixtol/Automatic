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
     * @var array
     */
    private $guards;

    /**
     *
     * @param StateInterface $currentState
     * @param Condition $condition
     * @param StateInterface $nextState
     * @param array $guards
     */
    public function __construct(StateInterface $currentState, ConditionInterface $condition, StateInterface $nextState, $guards = array()){
        $this->currentState = $currentState;
        $this->condition = $condition;
        $this->nextState = $nextState;
        $this->guards = $guards;
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

    /**
     *
     * @return array
     */
    public function getGuards(){
        return $this->guards;
    }
}