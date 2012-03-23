<?php

namespace Test\Mock;

use Automatic\Changeable;

class Item implements Changeable{

    private $state;

    private $name;

    public function __construct($state, $name = ''){
        $this->state = $state;
        $this->name = $name;
    }

    public function setStateKey($stateKey){
        $this->state = $stateKey;
    }

    public function getStateKey(){
        return $this->state;
    }

    public function getName(){
        return $this->name;
    }

}