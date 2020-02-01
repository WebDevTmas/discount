<?php

declare(strict_types=1);

namespace App\Tests\Application\Discounts;

use App\Application\Discounts\VolumeDiscount;
use App\Domain\Order;
use App\Domain\OrderLine;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class VolumeDiscountTest extends TestCase
{
    /**
     * @test
     * @dataProvider provider
     */
    public function itAddsFreeProductAndDiscount(
        int $lineQuantity,
        int $unitPrice,
        ?int $discount,
        int $quantityToBuy,
        int $freeQuantity,
        int $expectedTotal,
        int $expectedDiscount,
        int $expectedQuantity
    ) {
        $discount = null === $discount ? null :  Money::EUR($discount);

        $line  = new OrderLine(1, 'Product 1', $lineQuantity, Money::EUR($unitPrice), $discount);
        $order = new Order([$line], new Currency('EUR'));

        $discount = new VolumeDiscount($quantityToBuy, $freeQuantity);
        $discount->applyToOrder($order);

        $this->assertEquals((string) $expectedTotal, $order->getTotal()->getAmount());
        $this->assertEquals((string) $expectedDiscount, $order->getLines()[0]->getDiscount()->getAmount());
        $this->assertEquals($expectedQuantity, $order->getLines()[0]->getQuantity());
    }

    /**
     * @return array
     */
    public function provider(): array
    {
        return [
            [3, 400, null, 2, 1, 800, 400, 3],
            [2, 400, null, 2, 1, 800, 400, 3],
            [2, 400, null, 3, 1, 800, 0, 2],
            [6, 400, null, 2, 1, 2400, 1200, 9],
            [3, 400, 300, 2, 1, 500, 700, 3],
        ];
    }
}
