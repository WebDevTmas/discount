<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\ApplyDiscounts;
use App\Domain\Order;
use App\Infrastructure\RequestValidator\OrderRequestValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscountController
{
    /**
     * @var ApplyDiscounts
     */
    private $applyDiscounts;

    /**
     * DiscountController constructor.
     *
     * @param ApplyDiscounts $applyDiscounts
     */
    public function __construct(ApplyDiscounts $applyDiscounts)
    {
        $this->applyDiscounts = $applyDiscounts;
    }

    /**
     * @Route(path="/", methods={"POST"})
     */
    public function applyDiscount(Request $request): Response
    {
        $validator = new OrderRequestValidator();
        $response  = $validator->validate($request);
        if (null !== $response) {
            return $response;
        }

        $order = Order::fromArray($request->request->get('order'));
        $this->applyDiscounts->applyToOrder($order);

        return new JsonResponse(['order' => $order->toArray()]);
    }
}
