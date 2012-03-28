<?php

namespace Test\Mock\Guard;

use Test\Mock\Item;

use Automatic\Automatable;

use Automatic\Guard;

class OnlyBook implements Guard
{
    /**
     *
     * @var string
     */
    private $error;

    /**
     * (non-PHPdoc)
     * @see Automatic.Guard::isSafe()
     */
    public function isSafe(Automatable $automatable){
        if( !$automatable instanceof Item ){
            return true;
        }

        if( $automatable->getName() == "Book" ){
            return true;
        }

        $this->error = "El elemento no es un Book";
        return false;

    }

    /**
     * (non-PHPdoc)
     * @see Automatic.Guard::getLastError()
     */
    public function getLastError(){
        return $this->error;
    }

}