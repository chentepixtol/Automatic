<?php

namespace Automatic;

/**
 *
 * @author chente
 *
 */
class Condition implements ConditionInterface
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