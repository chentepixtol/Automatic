<?php

namespace Automatic;

/**
 *
 * TransitionCollection
 *
 * @author chente
 */
class TransitionCollection extends \ArrayIterator
{

    /**
     *
     *
     * @return TransitionCollection
     */
    public function newInstance(){
        return new static();
    }

    /**
     *
     * validate transition
     * @param Transition $transition
     */
    protected function validate($transition)
    {
        if( !($transition instanceof Transition) ){
            throw new \InvalidArgumentException("Debe de cumplir con la Interface Transition");
        }
    }

    /**
     *
     * validate Callback
     * @param callable $callable
     * @throws \InvalidArgumentException
     */
    protected function validateCallback($callable)
    {
        if( !is_callable($callable) ){
            throw new \InvalidArgumentException("Is not a callable function");
        }
    }

    /**
     * Appends the value
     * @param Transition $transition
     */
    public function append($transition)
    {
        $this->validate($transition);
        parent::offsetSet($transition->getIndex(), $transition);
        $this->rewind();
    }

    /**
     *
     * @param array $transitions
     */
    public function appendFromArray($transitions){
        foreach ($transitions as $transition){
            $this->append($transition);
        }
    }

    /**
     * Return current array entry
     * @return Transition
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and
     * move to next entry
     * @return Transition
     */
    public function read()
    {
        $transition = $this->current();
        $this->next();
        return $transition;
    }

    /**
     * Get the first array entry
     * if exists or null if not
     * @return Transition|null
     */
    public function getOne()
    {
        if ($this->count() > 0)
        {
            $this->seek(0);
            return $this->current();
        } else
            return null;
    }

    /**
     * Contains one transition with $name
     * @param  int $index
     * @return boolean
     */
    public function containsIndex($index)
    {
        return parent::offsetExists($index);
    }

    /**
     *
     * @param array $array
     * @return boolean
     */
    public function containsAll($ids)
    {
        if( $this->isEmpty() || empty($ids) ){
            return false;
        }

        $containsAll = true;
        foreach( $ids as $index ){
            $containsAll = $containsAll && $this->containsIndex($index);
            if( false === $containsAll ){
                break;
            }
        }
        return $containsAll;
    }

    /**
     *
     * @param array $ids
     * @return boolean
     */
    public function containsAny($ids)
    {
        if( $this->isEmpty() || empty($ids) ){
            return false;
        }

        foreach( $ids as $index ){
            if( $this->containsIndex($index) ){
                return true;
            }
        }
        return false;
    }

    /**
     * Remove one transition with $name
     * @param  int $index
     */
    public function remove($index)
    {
        if( $this->containsIndex($index) )
            $this->offsetUnset($index);
    }

    /**
     * Merge two TransitionCollection
     * @param TransitionCollection $transitionTransitionCollection
     * @return TransitionCollection
     */
    public function merge(TransitionCollection $transitionTransitionCollection)
    {
        $newTransitionCollection = $this->copy();
        $transitionTransitionCollection->each($this->appendFunction($newTransitionCollection));
        return $newTransitionCollection;
    }

    /**
     * @return TransitionCollection
     */
    public function copy()
    {
        $newTransitionCollection = $this->newInstance();
        $this->each($this->appendFunction($newTransitionCollection));
        return $newTransitionCollection;
    }

    /**
     * Diff two TransitionCollections
     * @param TransitionCollection $transitionTransitionCollection
     * @return TransitionCollection
     */
    public function diff(TransitionCollection $transitionTransitionCollection)
    {
        $newTransitionCollection = $this->newInstance();
        $this->each(function(Transition $transition) use($newTransitionCollection, $transitionTransitionCollection){
            if( !$transitionTransitionCollection->containsIndex($transition->getIndex()) ){
                $newTransitionCollection->append($transition);
            }
        });
        return $newTransitionCollection;
    }

    /**
     * Intersect two TransitionCollection
     * @param TransitionCollection $transitionTransitionCollection
     * @return TransitionCollection
     */
    public function intersect(TransitionCollection $transitionTransitionCollection)
    {
        $newTransitionCollection = $this->newInstance();
        $this->each(function(Transition $transition) use($newTransitionCollection, $transitionTransitionCollection){
            if( $transitionTransitionCollection->containsIndex($transition->getIndex()) ){
                $newTransitionCollection->append($transition);
            }
        });
        return $newTransitionCollection;
    }

    /**
     * Retrieve the array with primary keys
     * @return array
     */
    public function getPrimaryKeys()
    {
        return array_keys($this->getArrayCopy());
    }

    /**
     * Retrieve the Transition with primary key
     * @param  int $name
     * @return Transition
     */
    public function getByPK($index)
    {
        return $this->containsIndex($index) ? $this[$index] : null;
    }

    /**
     * Is Empty
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }

    /**
     *
     * @param \Closure $callable
     */
    public function each($callable)
    {
        $this->validateCallback($callable);

        $this->rewind();
        while( $this->valid() )
        {
            $transition = $this->read();
            $callable($transition);
        }
        $this->rewind();
    }

    /**
     *
     * @param \Closure $callable
     * @return array
     */
    public function map($callable)
    {
        $this->validateCallback($callable);

        $array = array();
        $this->each(function(Transition $transition) use(&$array, $callable){
            $mapResult = $callable($transition);
            if( is_array($mapResult) ){
                foreach($mapResult as $key => $value){
                    $array[$key] = $value;
                }
            }else{
                $array[] = $mapResult;
            }
        });

        return $array;
    }

    /**
     *
     * @param \Closure $callable
     * @return TransitionCollection
     */
    public function filter($callable)
    {
        $this->validateCallback($callable);

        $newTransitionCollection = $this->newInstance();
        $this->each(function(Transition $transition) use($newTransitionCollection, $callable){
            if( $callable($transition) ){
                $newTransitionCollection->append($transition);
            }
        });

        return $newTransitionCollection;
    }

    /**
     *
     * @param mixed $stateKey
     * @return \Automatic\TransitionCollection
     */
    public function filterByCurrentStateKey($stateKey){
        return $this->filter(function(Transition $transition) use($stateKey){
            return $transition->getCurrentState()->getKey() == $stateKey;
        });
    }

    /**
     *
     * @param mixed $stateKey
     * @param mixed $conditionKey
     * @return \Automatic\Transition
     */
    public function get($currentStateKey, $conditionKey){
        return $this->filter(function(Transition $transition) use($currentStateKey, $conditionKey){
            return $transition->getCurrentState()->getKey() == $currentStateKey &&
            $transition->getCondition()->getKey() == $conditionKey;
        })->getOne();
    }

    /**
     * @param mixed $start
     * @param callable $callable
     * @return mixed
     */
    public function foldLeft($start, $callable)
    {
        $this->validateCallback($callable);
        $result = $start;
        $this->each(function(Transition $transition) use(&$result, $callable){
            $result = $callable($result, $transition);
        });
        return $result;
    }

    /**
     *
     * @param callable $callable
     * @return boolean
     */
    public function forall($callable)
    {
        if( $this->isEmpty() ) return false;
        $this->validateCallback($callable);
        return $this->foldLeft(true, function($boolean, Transition $transition) use($callable){
            return $boolean && $callable($transition);
        });
    }

    /**
     *
     * @param callable $callable
     * @return array
     */
    public function partition($callable)
    {
        $this->validateCallback($callable);

        $transitionTransitionCollections = array();
        $getTransitionCollection = $this->collectionGenerator($transitionTransitionCollections);
        $this->each(function(Transition $transition) use($getTransitionCollection, $callable){
            $getTransitionCollection($callable($transition))->append($transition);
        });

        return $transitionTransitionCollections;
    }

    /**
     *
     * @return array
     */
    public function getCurrentStates(){
        return $this->map(function (Transition $transition){
            return array($transition->getCurrentState()->getKey() => $transition->getCurrentState()->getName());
        });
    }

    /**
     *
     * @return array
     */
    public function getNextStates(){
        return $this->map(function (Transition $transition){
            return array($transition->getNextState()->getKey() => $transition->getNextState()->getName());
        });
    }

    /**
     *
     * @return array
     */
    public function getConditions(){
        return $this->map(function (Transition $transition){
            return array($transition->getCondition()->getKey() => $transition->getCondition()->getName());
        });
    }


    /**
     *
     * @param array $transitionTransitionCollections
     * @return \Closure
     */
    private function collectionGenerator(array & $transitionTransitionCollections){
        $self = $this;
        $getTransitionCollection = function($index) use(&$transitionTransitionCollections, $self){
            if( !isset($transitionTransitionCollections[$index]) ){
                $transitionTransitionCollections[$index] = $self->newInstance();
            }
            return $transitionTransitionCollections[$index];
        };
        return $getTransitionCollection;
    }

    /**
     *
     * @param TransitionCollection $newCollenction
     * @return \Closure
     */
    private function appendFunction($newTransitionCollection){
        $appendFunction = function(Transition $transition) use($newTransitionCollection){
            if( !$newTransitionCollection->containsIndex( $transition->getIndex() ) ){
                $newTransitionCollection->append($transition);
            }
        };
        return $appendFunction;
    }

}