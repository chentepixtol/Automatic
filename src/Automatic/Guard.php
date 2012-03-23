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
     * @param Changeable $changeable
     * @return boolean
     */
    function isSafe(Changeable $changeable);

    /**
     * @return string
     */
    function getLastError();

}
