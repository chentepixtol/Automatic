<?php

namespace Test\Mock;

use Automatic\Transition;

use Automatic\Automatable;
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
    public function apply(Automatable $automatable, Transition $transition, $variables = array()){
        if( $automatable instanceof Item ){
            $nextState = $transition->getNextState()->getKey();
            $automatable->setStateKey($nextState);
        }
    }

}