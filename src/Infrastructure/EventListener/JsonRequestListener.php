<?php

declare(strict_types=1);

namespace App\Infrastructure\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonRequestListener
{
    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (! $this->isJsonRequest($request)) {
            return;
        }

        if ($this->transformJsonBody($request)) {
            return;
        }

        $response = JsonResponse::create([ 'errors' => [ 'Unable to parse request.' ] ], 400);
        $event->setResponse($response);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function isJsonRequest(Request $request): bool
    {
        return 'json' === $request->getContentType();
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function transformJsonBody(Request $request)
    {
        $content = $request->getContent();

        if (empty($content)) {
            return true;
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        if ($data === null) {
            return true;
        }

        $request->request->replace($data);

        return true;
    }
}
