<?php

namespace App\Tests\Domain;

use App\Domain\OrderLine;
use Money\Money;
use PHPUnit\Framework\TestCase;

class OrderLineTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCalculateTotal()
    {
        $line = new OrderLine(1, 'Product 1', 3, Money::EUR(100), Money::EUR(10));
        $this->assertEquals('290', $line->getTotal()->getAmount());

        $line = new OrderLine(1, 'Product 2', 3, Money::EUR(100), null);
        $this->assertEquals('300', $line->getTotal()->getAmount());
    }

    /**
     * @test
     */
    public function itCanAddDiscount()
    {
        $line = new OrderLine(1, 'Product 3', 3, Money::EUR(100), null);
        $line->addDiscount(Money::EUR(20));
        $line->addDiscount(Money::EUR(30));
        $this->assertEquals('250', $line->getTotal()->getAmount());
    }
}
