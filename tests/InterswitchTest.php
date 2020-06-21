<?php


namespace Tests;


use OgunsakinDamilola\Interswitch\Interswitch;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;

class InterswitchTest extends TestCase
{
    private $interswitch;

    protected function setUp(): void
    {
        $this->interswitch = new Interswitch();
    }

    /**
     * Testing the generate payment method
     */
    public function testGeneratePayment()
    {
        $interswitchPayment = factory(InterswitchPayment::class)->make();
        $array = $this->interswitch->generatePayment($interswitchPayment);
        $this->assertIsArray($array);
        $this->assertEquals($array, $interswitchPayment);
    }
}
