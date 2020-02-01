<?php

declare(strict_types=1);

namespace App\Infrastructure\RequestValidator;

use Particle\Validator\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderRequestValidator implements RequestValidator
{
    /**
     * @param Request $request
     *
     * @return Response|null
     */
    public function validate(Request $request): ?Response
    {
        if (! $request->request->has('order')) {
            return new JsonResponse(
                [ 'errors' => [ 'property' => 'order', 'message' => 'Order not found in request.' ] ],
                400
            );
        }

        $validator = new Validator();

        $validator
            ->required('currency')
            ->equals('EUR'); // Only Allow EUR for now

        $validator
            ->required('lines')
            ->greaterThan(1)
            ->each(function (Validator $validator) {
                $validator->required('product_id')->integer()->greaterThan(0);
                $validator->required('product_name')->string()->allowEmpty(false);
                $validator->required('quantity')->integer()->greaterThan(0);
                $validator->required('unit_price')->integer()->greaterThan(0);
            });

        $result = $validator->validate($request->request->get('order'));
        if ($result->isValid()) {
            return null;
        }

        $messages = [];
        foreach ($result->getFailures() as $failure) {
            $messages[] = [
                'message' => $failure->format(),
                'property' => $failure->getKey()
            ];
        }

        return new JsonResponse([ 'errors' => $messages ], 400);
    }
}
