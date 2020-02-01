<?php

declare(strict_types=1);

namespace App\Application\Discounts;

use App\Domain\Order;
use App\Domain\OrderLine;

class PercentDiscount implements Discount
{
    /**
     * @var int
     */
    private $percentage;
    /**
     * @var int[]
     */
    private $productIds;

    /**
     * VolumeDiscount constructor.
     *
     * @param int   $percentage
     * @param int[] $productIds
     */
    public function __construct(int $percentage, int ...$productIds)
    {
        $this->percentage = $percentage;
        $this->productIds = $productIds;
    }

    /**
     * @param Order $order
     */
    public function applyToOrder(Order $order): void
    {
        foreach ($order->getLines() as $line) {
            $this->applyToOrderLine($line);
        }
    }

    /**
     * @param OrderLine $line
     */
    private function applyToOrderLine(OrderLine $line)
    {
        if (! in_array($line->getProductId(), $this->productIds)) {
            return;
        }

        $discount = $line->getTotal()->multiply($this->percentage / 100);
        $line->addDiscount($discount);
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 25;
    }
}
