<?php

namespace OgunsakinDamilola\Interswitch\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;
use OgunsakinDamilola\Interswitch\Tests\TestCase;

class InterswitchPaymentModelTest extends TestCase
{
    use RefreshDatabase;

    public $interswitchPayment;

    public function setUp(): void
    {
        parent::setUp();
        $this->setInterswitchPayment();
    }

    /** @test */
    public function model_can_be_initiated_with_factory()
    {
        $this->assertTrue($this->interswitchPayment instanceof InterswitchPayment);
    }

    /** @test */
    public function model_has_relevant_properties(){
        $model = $this->interswitchPayment->toArray();
        $this->assertArrayHasKey('id', $model);
        $this->assertArrayHasKey('customer_id', $model);
        $this->assertArrayHasKey('customer_name', $model);
        $this->assertArrayHasKey('customer_email', $model);
        $this->assertArrayHasKey('environment', $model);
        $this->assertArrayHasKey('gateway', $model);
        $this->assertArrayHasKey('reference', $model);
        $this->assertArrayHasKey('amount', $model);
        $this->assertArrayHasKey('response_code', $model);
        $this->assertArrayHasKey('response_description', $model);
    }

    private function setInterswitchPayment()
    {
        $this->interswitchPayment = factory(InterswitchPayment::class)->create();
    }
}
