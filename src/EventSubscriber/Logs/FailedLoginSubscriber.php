<?php

namespace App\EventSubscriber\Logs;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class FailedLoginSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $myLogsLogger)
    {
        $this->logger = $myLogsLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [ LoginFailureEvent::class => 'addFailedLogin'];
    }

    public function addFailedLogin(LoginFailureEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');
        if ($route === null || \strpos($route, 'api_') === false) {
            return;
        }

        $user = $event->getPassport()->getUser('email');
        $this->logger->info('Failed login', ['email' => $user->getUserIdentifier()]);
    }
}
