<?php

namespace Test\Mock;

use Automatic\Changeable;

class Item implements Changeable{

    private $state;

    public function __construct($state){
        $this->state = $state;
    }

    public function getStateKey(){
        return $this->state;
    }

}