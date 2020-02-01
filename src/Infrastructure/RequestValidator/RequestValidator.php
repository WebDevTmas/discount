<?php

declare(strict_types=1);

namespace App\Infrastructure\RequestValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestValidator
{
    /**
     * @param Request $request
     *
     * @return Response|null
     */
    public function validate(Request $request): ?Response;
}
