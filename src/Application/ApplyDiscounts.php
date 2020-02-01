<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\Discounts\Discount;
use App\Domain\Order;

class ApplyDiscounts
{
    /**
     * @var Discount[]
     */
    private $discounts;

    /**
     * ApplyDiscounts constructor.
     *
     * @param Discount ...$discounts
     */
    public function __construct(Discount ...$discounts)
    {
        usort(
            $discounts,
            function (Discount $discountA, Discount $discountB) {
                return $discountA->getPriority() > $discountB->getPriority() ? -1 : 1;
            }
        );

        $this->discounts = $discounts;
    }

    /**
     * @param Order $order
     */
    public function applyToOrder(Order $order): void
    {
        foreach ($this->discounts as $discount) {
            $discount->applyToOrder($order);
        }
    }
}
