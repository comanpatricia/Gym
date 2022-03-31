<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseContentSubscriber implements EventSubscriberInterface
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => 'encodeJsonResponseData'
        ];
    }

    public function encodeJsonResponseData(ViewEvent $event): void
    {
        $accept = $event->getRequest()->headers->get('Accept');

        if ($accept === 'application/json') {
            $event->setResponse(new JsonResponse());
        }
    }
}
