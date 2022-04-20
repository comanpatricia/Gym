<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class XmlResponseContentSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => 'encodeXmlResponseData'
        ];
    }

    public function encodeXmlResponseData(ViewEvent $event): void
    {
        $accept = $event->getRequest()->headers->get('Accept');

        if ($accept === 'application/xml') {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize(
                    $event->getControllerResult(),
                    'xml',
                    ['groups' => 'api:programme:all']
                ),
                Response::HTTP_OK,
                [],
                true,
            ));
        }
    }
}
