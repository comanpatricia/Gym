<?php

namespace App\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class GigelResponseContentSubscriber implements EventSubscriberInterface
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getSubscribedEvents()
    {
        return [
            ViewEvent::class => 'encodeGigelResponseData'
        ];
    }

    public function encodeGigelResponseData(ViewEvent $event): void
    {
        $accept = $event->getRequest()->headers->get('Accept');

        if ($accept === 'application/gigel') {
            $event->setResponse(new Response(
                $xml = $this->serializer->serialize(
                    $event,
                    'gigel',
                    ['groups' => 'api:programme:all']
                ),
                Response::HTTP_OK
            ));
        }
    }
}
