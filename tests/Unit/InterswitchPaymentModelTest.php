<?php
namespace OgunsakinDamilola\Interswitch\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OgunsakinDamilola\Interswitch\Models\InterswitchPayment;
use OgunsakinDamilola\Interswitch\Tests\TestCase;

class InterswitchPaymentModelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function model_can_be_initiated_with_factory()
    {
        $model = factory(InterswitchPayment::class)->create();

        $this->assertTrue($model instanceof InterswitchPayment);
    }
}
