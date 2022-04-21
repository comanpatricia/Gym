<?php

namespace App\EventSubscriber\Logs;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class SuccessLoginSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $myLogsLogger)
    {
        $this->logger = $myLogsLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [ LoginSuccessEvent::class => 'addSuccessLogin'];
    }

    public function addSuccessLogin(LoginSuccessEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');
        if ($route === null || \strpos($route, 'api_') === false) {
            return;
        }

        $user = $event->getUser();
        $this->logger->info('Success login', ['user' => $user->getUserIdentifier()]);
    }
}
