<?php

declare(strict_types=1);

namespace App\Tests\Application\Discounts;

use App\Application\Discounts\PercentDiscount;
use App\Domain\Order;
use App\Domain\OrderLine;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class PercentDiscountTest extends TestCase
{
    /**
     * @test
     */
    public function itAppliesToOrder()
    {
        $line1  = new OrderLine(1, 'Product 1', 3, Money::EUR(200), null);
        $line2  = new OrderLine(2, 'Product 2', 3, Money::EUR(200), Money::EUR(100));
        $line3  = new OrderLine(3, 'Product 3', 3, Money::EUR(200), null);
        $order  = new Order([$line1, $line2, $line3], new Currency('EUR'));

        $discount = new PercentDiscount(10, 1, 2);
        $discount->applyToOrder($order);

        $this->assertEquals('60', $order->getLines()[0]->getDiscount()->getAmount());
        $this->assertEquals('150', $order->getLines()[1]->getDiscount()->getAmount());
        $this->assertEquals('0', $order->getLines()[2]->getDiscount()->getAmount());
    }
}
