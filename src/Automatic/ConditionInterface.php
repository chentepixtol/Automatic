<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
interface ConditionInterface{

    /**
     * @return mixed
     */
    function getKey();

    /**
     * @return string
     */
    function getName();
}