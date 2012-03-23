<?php

namespace Test\Mock;

use Automatic\Transition;

use Automatic\Changeable;
use Automatic\Handler;

/**
 *
 * @author chente
 *
 */
class ChangeStatusHandler implements Handler{

    /**
     * (non-PHPdoc)
     * @see Automatic.Handler::apply()
     */
    public function apply(Changeable $changeable, Transition $transition, $variables = array()){
        if( $changeable instanceof Item ){
            $nextState = $transition->getNextState()->getKey();
            $changeable->setStateKey($nextState);
        }
    }

}