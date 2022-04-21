<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ResponseContentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => ['encodeResponseData', -10]
        ];
    }

    public function encodeResponseData(ViewEvent $event): void
    {
        $event->setResponse(new JsonResponse(
            'Content type not supported',
            Response::HTTP_NOT_ACCEPTABLE,
            [],
            true
        ));
    }
}
