<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
interface State{

    /**
     * @return mixed
     */
    function getKey();

    /**
     * @return string
     */
    function getName();
}