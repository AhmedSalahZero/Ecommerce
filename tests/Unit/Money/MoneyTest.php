<?php

namespace Tests\Unit\Money;

use App\cart\Money;
use PHPUnit\Framework\TestCase;
use Money\Money as BaseMoney ;

class MoneyTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_returns_raw_money()
    {
        $money = new Money(1000);
        $this->assertEquals(1000 , $money->amount());
    }
    public function test_it_returns_formatted_money()
    {
        $money = new Money(1000);
        $this->assertEquals("$10.00" , $money->formatted());

    }
    public function test_it_can_add_up()
    {
        $money = new Money(1000);
        $money1=new Money(2000);

        $sum = $money->add($money1);

        $this->assertEquals(3000 , $sum->amount());

    }
    public function test_it_return_the_underlying_instance()
    {
        $money = new Money(1000);
        $this->assertInstanceOf(BaseMoney::class , $money->instance());

    }

}
