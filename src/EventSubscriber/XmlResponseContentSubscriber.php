<?php

namespace App\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class XmlResponseContentSubscriber implements EventSubscriberInterface
{
    protected \Symfony\Component\Serializer\SerializerInterface $serializer;

    public function __construct(\Symfony\Component\Serializer\SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => 'encodeXmlResponseData'
        ];
    }

    public function encodeXmlResponseData(ViewEvent $event): void
    {
        $accept = $event->getRequest()->headers->get('Accept');

        if ($accept === 'application/xml') {
            $event->setResponse(new Response(
                $xml = $this->serializer->serialize(
                    $event,
                    'xml',
                    ['groups' => 'api:programme:all']
                ),
                Response::HTTP_OK,
                ['Content-Type' => 'application/xml'],
            ));
        }
    }
}
