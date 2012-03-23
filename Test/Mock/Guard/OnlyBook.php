<?php

namespace Test\Mock\Guard;

use Test\Mock\Item;

use Automatic\Changeable;

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
    public function isSafe(Changeable $changeable){
        if( !$changeable instanceof Item ){
            return true;
        }

        if( $changeable->getName() == "Book" ){
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