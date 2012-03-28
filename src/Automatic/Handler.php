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
     * @param Automatable $automatable
     * @param Transition $transition
     * @param array $variables
     */
    public function apply(Automatable $automatable, Transition $transition, $variables = array());

}