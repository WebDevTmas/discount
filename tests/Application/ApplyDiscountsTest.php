<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Application\ApplyDiscounts;
use App\Application\Discounts\PercentDiscount;
use App\Application\Discounts\VolumeDiscount;
use App\Domain\Order;
use App\Domain\OrderLine;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class ApplyDiscountsTest extends TestCase
{
    /**
     * @test
     */
    public function itAppliesToOrder()
    {
        $volumeDiscount   = new VolumeDiscount(3, 1);
        $percentDiscount  = new PercentDiscount(10, 4, 5);
        $applyToDiscounts = new ApplyDiscounts($volumeDiscount, $percentDiscount);

        $line1  = new OrderLine(1, 'Product 1', 3, Money::EUR(200), null);
        $line2  = new OrderLine(2, 'Product 2', 3, Money::EUR(200), Money::EUR(100));
        $line3  = new OrderLine(4, 'Product 4', 3, Money::EUR(200), null);
        $order  = new Order([$line1, $line2, $line3], new Currency('EUR'));

        $applyToDiscounts->applyToOrder($order);

        $this->assertEquals(4, $order->getLines()[0]->getQuantity());
        $this->assertEquals('200', $order->getLines()[0]->getDiscount()->getAmount());

        $this->assertEquals(4, $order->getLines()[1]->getQuantity());
        $this->assertEquals('300', $order->getLines()[1]->getDiscount()->getAmount());

        $this->assertEquals(4, $order->getLines()[2]->getQuantity());
        $this->assertEquals('260', $order->getLines()[2]->getDiscount()->getAmount());
    }
}
