<?php

declare(strict_types=1);

namespace App\Domain;

use Assert\AssertionFailedException;
use Money\Currency;
use Money\Money;
use Assert\Assertion;

class Order
{
    /**
     * @var OrderLine[]
     */
    private $lines;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * Order constructor.
     *
     * @param array $lines
     *
     * @throws AssertionFailedException
     */
    public function __construct(array $lines, Currency $currency)
    {
        Assertion::minCount($lines, 1, 'An order requires at least 1 order line.');

        $this->lines    = $lines;
        $this->currency = $currency;
    }

    /**
     * @return OrderLine[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }

    /**
     * @return Money
     */
    public function getTotal(): Money
    {
        $totals = array_map(
            function (OrderLine $line) {
                return $line->getTotal();
            },
            $this->lines
        );

        return Money::sum(...$totals);
    }

    /**
     * @param array $orderArray
     *
     * @return Order
     *
     * @throws AssertionFailedException
     */
    public static function fromArray(array $orderArray): Order
    {
        $currency = new Currency($orderArray['currency']);

        $lines = [];
        foreach ($orderArray['lines'] as $lineArray) {
            $lines[] = OrderLine::fromArray($lineArray, $currency);
        }

        return new Order($lines, $currency);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $orderArray = [
            'lines' => [],
            'currency' => $this->currency->getCode()
        ];

        foreach ($this->getLines() as $line) {
            $orderArray['lines'][] = $line->toArray();
        }

        return $orderArray;
    }
}
