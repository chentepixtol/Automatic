<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
interface StateInterface{

    /**
     * @return mixed
     */
    function getKey();

    /**
     * @return string
     */
    function getName();
}