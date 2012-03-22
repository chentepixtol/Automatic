<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
class State implements StateInterface
{

    /**
     *
     * @var mixed
     */
    private $key;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @param mixed $key
     * @param string $name
     */
    public function __construct($key, $name){
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getKey(){
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }
}