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
     * @var State
     */
    private $currentState;

    /**
     *
     * @var Condition
     */
    private $condition;

    /**
     *
     * @var State
     */
    private $nextState;

    /**
     *
     * @var array
     */
    private $guards;

    /**
     *
     * @param State $currentState
     * @param Condition $condition
     * @param State $nextState
     * @param array $guards
     */
    public function __construct(State $currentState, Condition $condition, State $nextState, $guards = array()){
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
     * @return \Automatic\State
     */
    public function getCurrentState(){
        return $this->currentState;
    }

    /**
     *
     * @return \Automatic\State
     */
    public function getNextState(){
        return $this->nextState;
    }

    /**
     *
     * @return \Automatic\Condition
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