<?php

declare(strict_types=1);

namespace App\Application\Discounts;

use App\Domain\Order;

interface Discount
{
    /**
     * @param Order $order
     */
    public function applyToOrder(Order $order): void;

    /**
     * Discounts with a higher priority get applied first
     *
     * @return int
     */
    public function getPriority(): int;
}
