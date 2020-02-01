<?php

declare(strict_types=1);

namespace App\Domain;

use Assert\AssertionFailedException;
use Assert\Assertion;
use Money\Currency;
use Money\Money;
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractServiceConfigurator;

class OrderLine
{
    /**
     * @var int
     */
    private $productId;

    /**
     * @var string
     */
    private $productName;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var Money
     */
    private $unitPrice;

    /**
     * @var Money
     */
    private $discount;

    /**
     * OrderLine constructor.
     *
     * @param int        $productId
     * @param string     $productName
     * @param int        $quantity
     * @param Money      $unitPrice
     * @param Money|null $discount
     *
     * @throws AssertionFailedException
     */
    public function __construct(int $productId, string $productName, int $quantity, Money $unitPrice, ?Money $discount)
    {
        Assertion::greaterOrEqualThan($productId, 1, 'Invalid product ID');
        Assertion::notBlank($productName, 'Product name is required');
        Assertion::greaterOrEqualThan($quantity, 1, 'Invalid product quantity');
        Assertion::true($unitPrice->isPositive(), 'Invalid unit price');
        if (null !== $discount) {
            Assertion::true($discount->isPositive() || $discount->isZero(), 'Invalid discount');
        }

        $this->productId   = $productId;
        $this->productName = $productName;
        $this->quantity    = $quantity;
        $this->unitPrice   = $unitPrice;
        $this->discount    = $discount ?? new Money(0, $unitPrice->getCurrency());
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return Money
     */
    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }

    /**
     * @return Money
     */
    public function getDiscount(): Money
    {
        return $this->discount;
    }

    /**
     * @param Money $discount
     */
    public function addDiscount(Money $discount): void
    {
        $this->discount = $this->discount->add($discount);
    }

    /**
     * @return Money
     */
    public function getTotal(): Money
    {
        return $this->unitPrice->multiply($this->quantity)->subtract($this->discount);
    }

    /**
     * @param array    $lineArray
     * @param Currency $currency
     *
     * @return OrderLine
     * @throws AssertionFailedException
     */
    public static function fromArray(array $lineArray, Currency $currency): OrderLine
    {
        return new self(
            $lineArray['product_id'],
            $lineArray['product_name'],
            $lineArray['quantity'],
            new Money($lineArray['unit_price'], $currency),
            null
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->getProductId(),
            'product_name' => $this->getProductName(),
            'quantity' => $this->getQuantity(),
            'unit_price' => (int) $this->getUnitPrice()->getAmount(),
            'discount' => (int) $this->getDiscount()->getAmount()
        ];
    }
}
