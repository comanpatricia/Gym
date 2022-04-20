<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseContentSubscriber
//    implements EventSubscriberInterface
{
//    private SerializerInterface $serializer;
//
//    private GigelResponseContentSubscriber $gigelResponseContentSubscriber;
//
//    private XmlResponseContentSubscriber $xmlResponseContentSubscriber;
//
//    private JsonResponseContentSubscriber $jsonResponseContentSubscriber;
//
//    public function __construct(
//        SerializerInterface $serializer,
//        GigelResponseContentSubscriber $gigelResponseContentSubscriber,
//        XmlResponseContentSubscriber $xmlResponseContentSubscriber,
//        JsonResponseContentSubscriber $jsonResponseContentSubscriber
//    ) {
//        $this->serializer = $serializer;
//        $this->gigelResponseContentSubscriber = $gigelResponseContentSubscriber;
//        $this->xmlResponseContentSubscriber = $xmlResponseContentSubscriber;
//        $this->jsonResponseContentSubscriber = $jsonResponseContentSubscriber;
//    }
//
//    public static function getSubscribedEvents()
//    {
//        return [
//            ViewEvent::class => [
//                ['encodeJsonResponseData', 3],
//                ['encodeXmlResponseData', 2],
//                ['encodeGigelResponseData', 1],
//            ]
//        ];
//    }
//
//    public function encodeResponseData(ViewEvent $event): void
//    {
//
//    }
}
