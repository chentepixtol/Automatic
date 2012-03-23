<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
interface Condition{

    /**
     * @return mixed
     */
    function getKey();

    /**
     * @return string
     */
    function getName();
}