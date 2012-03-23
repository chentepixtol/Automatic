<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
interface Handler
{

    /**
     *
     * @param Changeable $changeable
     * @param Transition $transition
     * @param array $variables
     */
    public function apply(Changeable $changeable, Transition $transition, $variables = array());

}