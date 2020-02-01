<?php

namespace App\Tests\Domain;

use App\Domain\Order;
use App\Domain\OrderLine;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    /**
     * @test
     */
    public function itCanCalculateTotal()
    {
        $line1 = new OrderLine(1, 'Product 1', 3, Money::EUR(100), Money::EUR(10));
        $line2 = new OrderLine(1, 'Product 1', 1, Money::EUR(800), null);
        $line3 = new OrderLine(1, 'Product 1', 1, Money::EUR(100), Money::EUR(10));
        $order = new Order([$line1, $line2, $line3], new Currency('EUR'));

        $this->assertEquals('1180', $order->getTotal()->getAmount());
    }
}
