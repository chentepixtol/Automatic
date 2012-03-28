<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
interface Guard
{

    /**
     *
     * @param Automatable $automatable
     * @return boolean
     */
    function isSafe(Automatable $automatable);

    /**
     * @return string
     */
    function getLastError();

}
