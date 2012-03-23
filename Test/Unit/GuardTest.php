<?php

namespace Test\Unit;


/**
 *
 * @author chente
 *
 */
use Test\Mock\Guard;
use Automatic\TransitionCollection;
use Test\Mock\Item;
use Automatic\Condition;
use Automatic\State;
use Automatic\Transition;
use Automatic\Machine;

class GuardTest extends BaseTest
{

    /**
     * @test
     */
    public function guards(){

        // conditions
        $prestar = new Condition(1, "Prestar");
        $comprar = new Condition(2, "Comprar");
        $devolver = new Condition(3, "Devolver");

        // status
        $estante = new State(1, "Estante");
        $prestado = new State(2, "Prestado");
        $comprado = new State(3, "Comprado");


        $guards = array(new Guard\OnlyBook());
        $transitions = new TransitionCollection();
        $transitions->appendFromArray(array(
            new Transition($estante, $prestar, $prestado, $guards),
            new Transition($estante, $comprar, $comprado),
            new Transition($prestado, $devolver, $estante),
        ));
        $machine = new Machine($transitions);

        $this->assertTrue($machine->isCappable(new Item(1, "Book"), $prestar->getKey()));
        $this->assertFalse($machine->isCappable(new Item(1, "Magazine"), $prestar->getKey()));
    }

}

