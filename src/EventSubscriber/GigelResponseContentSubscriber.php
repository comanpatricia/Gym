<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class GigelResponseContentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => ['encodeGigelResponseData', 10]
        ];
    }

    public function encodeGigelResponseData(ViewEvent $event): void
    {
        $accept = $event->getRequest()->headers->get('Accept');
        if ($accept === 'application/gigel') {
                $event->setResponse(new JsonResponse('hello sunt gigel'));
        }
    }
}
