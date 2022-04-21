<?php

namespace App\EventSubscriber;

use App\Repository\ProgrammeRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;

use function Symfony\Component\Translation\t;

class GigelResponseContentSubscriber implements EventSubscriberInterface
{
    private ProgrammeRepository $programmeRepository;

    public function __construct(ProgrammeRepository $programmeRepository)
    {
        $this->programmeRepository = $programmeRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => ['encodeGigelResponseData', 10]
        ];
    }

    public function encodeGigelResponseData(ViewEvent $event): void
    {
        $programmeNumber = $event->getControllerResult();

        $accept = $event->getRequest()->headers->get('Accept');
        if ($accept === 'application/gigel') {
                $event->setResponse(new JsonResponse(\array_map(function () {
                    return ['hello' => 'sunt gigel'];
                }, $programmeNumber)));
        }
    }
}
