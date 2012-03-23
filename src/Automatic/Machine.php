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
     * @param TransitionCollection $transitionCollection
     */
    public function __construct(TransitionCollection $transitionCollection){
        $this->transitionCollection = $transitionCollection;
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
            return false;
        }

        $transition = $this->transitionCollection->get($changeable->getStateKey(), $conditionKey);
        foreach( $transition->getGuards() as $guard ){
            /* @var $guard \Automatic\Guard */
            if( !$guard->isSafe($changeable) ){
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
    public function handle(Changeable $changeable, $conditionKey)
    {

    }


}
