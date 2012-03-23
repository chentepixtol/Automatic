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
use Test\Mock\Condition;
use Test\Mock\State;
use Automatic\Transition;
use Automatic\Machine;

class GuardTest extends BaseTest
{
    /**
     *
     * Conditions
     */
    const PRESTAR = 1;
    const COMPRAR = 2;
    const DEVOLVER = 3;

    /**
     *
     * Status
     */
    const ESTANTE = 1;
    const PRESTADO = 2;
    const COMPRADO = 3;

    private $machine;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        // conditions
        $prestar = new Condition(self::PRESTAR, "Prestar");
        $comprar = new Condition(self::COMPRAR, "Comprar");
        $devolver = new Condition(self::DEVOLVER, "Devolver");

        // status
        $estante = new State(self::ESTANTE, "Estante");
        $prestado = new State(self::PRESTADO, "Prestado");
        $comprado = new State(self::COMPRADO, "Comprado");


        $guards = array(new Guard\OnlyBook());
        $transitions = new TransitionCollection();
        $transitions->appendFromArray(array(
            new Transition($estante, $prestar, $prestado, $guards),
            new Transition($estante, $comprar, $comprado),
            new Transition($prestado, $devolver, $estante),
        ));
        $this->machine = new Machine($transitions);
    }

    /**
     * @test
     */
    public function guards()
    {
        $this->assertTrue($this->machine->isCappable(new Item(self::ESTANTE, "Book"), self::PRESTAR));
        $this->assertFalse($this->machine->isCappable(new Item(self::ESTANTE, "Magazine"), self::PRESTAR));
    }

    /**
     *
     */
    public function handle(){
        $item = new Item(self::ESTANTE, "Book");
        $this->machine->handle($item, self::PRESTAR);
        $this->assertEquals(self::PRESTADO, $item->getStateKey());
    }

}

