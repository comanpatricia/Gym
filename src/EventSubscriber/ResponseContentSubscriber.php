<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseContentSubscriber
    implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            ViewEvent::class => [
                [

                ],
            ]
        ];
    }

    public function encodeResponseData(ViewEvent $event): void
    {

    }
}
