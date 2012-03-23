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
     * @param Changeable $changeable
     * @return array
     */
    public function getNextStates(Changeable $changeable){
        return $this->transitionCollection
            ->filterByCurrentStateKey($changeable->getStateKey())
            ->getNextStates();
    }

    /**
     *
     * @param Changeable $changeable
     * @return array
     */
    public function getNextStateKeys(Changeable $changeable){
        return array_keys($this->getNextStates($changeable));
    }

    /**
     *
     * @param Changeable $changeable
     * @param mixed $stateKey
     * @return boolean
     */
    public function isValidNextState(Changeable $changeable, $stateKey){
        return in_array($stateKey, $this->getNextStateKeys($changeable));
    }

    /**
     *
     * @param Changeable $changeable
     * @return array
     */
    public function getValidConditions(Changeable $changeable){
        return $this->transitionCollection
            ->filterByCurrentStateKey($changeable->getStateKey())
            ->getConditions();
    }

    /**
     *
     * @param Changeable $changeable
     * @return array
     */
    public function getValidConditionKeys(Changeable $changeable){
        return array_keys($this->getValidConditions($changeable));
    }

    /**
     *
     * @param Changeable $changeable
     * @param mixed $conditionKey
     * @return boolean
     */
    public function isValidCondition(Changeable $changeable, $conditionKey){
        return in_array($conditionKey, $this->getValidConditionKeys($changeable));
    }

    /**
     *
     * @param Changeable $changeable
     * @param mixed $conditionKey
     * @return boolean
     */
    public function isCappable(Changeable $changeable, $conditionKey)
    {
        if( !$this->isValidCondition($changeable, $conditionKey) ){
            $this->lastError = "The transition from " . $changeable->getStateKey().
                " with condition " .$conditionKey  . " not exists";
            return false;
        }

        $transition = $this->transitionCollection->get($changeable->getStateKey(), $conditionKey);
        foreach( $transition->getGuards() as $guard ){
            /* @var $guard \Automatic\Guard */
            if( !$guard->isSafe($changeable) ){
                $this->lastError = $guard->getLastError();
                return false;
            }
        }

        return true;
    }

    /**
     *
     * @param Changeable $changeable
     * @param mixed $conditionKey
     */
    public function handle(Changeable $changeable, $conditionKey, $variables = array())
    {
        if( !$this->isCappable($changeable, $conditionKey) ){
            throw new AutomataException($this->lastError);
        }
        $transition = $this->transitionCollection->get($changeable->getStateKey(), $conditionKey);
        $this->successHandler->apply($changeable, $transition, $variables);
    }


}
