<?php

declare(strict_types=1);

namespace App\Application\Discounts;

use App\Domain\Order;
use App\Domain\OrderLine;

class VolumeDiscount implements Discount
{
    /**
     * @var int
     */
    private $quantityToBuy;
    /**
     * @var int
     */
    private $freeQuantity;

    /**
     * VolumeDiscount constructor.
     *
     * @param int $quantityToBuy
     * @param int $freeQuantity
     */
    public function __construct(int $quantityToBuy, int $freeQuantity)
    {

        $this->quantityToBuy = $quantityToBuy;
        $this->freeQuantity  = $freeQuantity;
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
        $lineQuantityWithoutFreeProducts = (int) floor($line->getQuantity() / $this->quantityToBuy)
            * $this->quantityToBuy;

        $freeProducts = (int) round($lineQuantityWithoutFreeProducts / $this->quantityToBuy) * $this->freeQuantity;

        if (0 === $freeProducts) {
            return;
        }

        $line->setQuantity($lineQuantityWithoutFreeProducts + $freeProducts);
        $line->addDiscount($line->getUnitPrice()->multiply($freeProducts));
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 50;
    }
}
