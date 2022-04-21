<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CustomResponseHeaderSubscriber implements EventSubscriberInterface
{
    private string $apiVersion;

    public function __construct(string $apiVersion)
    {
        $this->apiVersion = $apiVersion;
    }

    public static function getSubscribedEvents(): array
    {
        return [ResponseEvent::class => 'addApiVersionHeader'];
    }

    public function addApiVersionHeader(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest()->attributes->get('_route');

        if ($request === null || \strpos($request, 'api_') === false) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set('X-Api-Version', $this->apiVersion);
    }
}
