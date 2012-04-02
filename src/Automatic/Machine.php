<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
class Machine
{

    /**
     *
     * @var TransitionCollection
     */
    private $transitionCollection;

    /**
     *
     * @var Handler
     */
    private $successHandler;

    /**
     *
     * @var string
     */
    private $lastError;

    /**
     *
     * @param TransitionCollection $transitionCollection
     */
    public function __construct(TransitionCollection $transitionCollection, Handler $successHandler = null){
        $this->transitionCollection = $transitionCollection;
        $this->successHandler = ( null == $successHandler )? new NullHandler() : $successHandler;
    }

    /**
     *
     * @param Automatable $automatable
     * @return array
     */
    public function getNextStates(Automatable $automatable){
        return $this->transitionCollection
            ->filterByCurrentStateKey($automatable->getStateKey())
            ->getNextStates();
    }

    /**
     *
     * @param Automatable $automatable
     * @return array
     */
    public function getNextStateKeys(Automatable $automatable){
        return array_keys($this->getNextStates($automatable));
    }

    /**
     *
     * @param Automatable $automatable
     * @param mixed $stateKey
     * @return boolean
     */
    public function isValidNextState(Automatable $automatable, $stateKey){
        return in_array($stateKey, $this->getNextStateKeys($automatable));
    }

    /**
     *
     * @param Automatable $automatable
     * @return array
     */
    public function getValidConditions(Automatable $automatable){
        return $this->transitionCollection
            ->filterByCurrentStateKey($automatable->getStateKey())
            ->getConditions();
    }

    /**
     *
     * @param Automatable $automatable
     * @return array
     */
    public function getValidConditionKeys(Automatable $automatable){
        return array_keys($this->getValidConditions($automatable));
    }

    /**
     *
     * @param Automatable $automatable
     * @param mixed $conditionKey
     * @return boolean
     */
    public function isValidCondition(Automatable $automatable, $conditionKey){
        return in_array($conditionKey, $this->getValidConditionKeys($automatable));
    }

    /**
     *
     * @param Automatable $automatable
     * @param mixed $conditionKey
     * @return boolean
     */
    public function isCappable(Automatable $automatable, $conditionKey)
    {
        if( !$this->isValidCondition($automatable, $conditionKey) ){
            $this->lastError = "The transition from " . $automatable->getStateKey().
                " with condition " .$conditionKey  . " not exists";
            return false;
        }

        $transition = $this->transitionCollection->get($automatable->getStateKey(), $conditionKey);
        foreach( $transition->getGuards() as $guard ){
            /* @var $guard \Automatic\Guard */
            if( !$guard->isSafe($automatable) ){
                $this->lastError = $guard->getLastError();
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @param Automatable $automatable
     * @param unknown_type $conditionKey
     * @return boolean
     */
    public function isCappableByConditionName(Automatable $automatable, $conditionName){
        $conditionKey = array_search($conditionName, $this->transitionCollection->getConditions());
        if( false === $conditionKey ){
            throw new AutomataException("The condition {$conditionName} not exists.");
        }
        return $this->isCappable($automatable, $conditionKey);
    }

    /**
     *
     * @param Automatable $automatable
     * @param mixed $conditionKey
     */
    public function handle(Automatable $automatable, $conditionKey, $variables = array())
    {
        if( !$this->isCappable($automatable, $conditionKey) ){
            throw new AutomataException($this->lastError);
        }
        $transition = $this->transitionCollection->get($automatable->getStateKey(), $conditionKey);
        $this->successHandler->apply($automatable, $transition, $variables);
    }


}
