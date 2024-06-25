<?php

namespace App\Event;

use App\IA\Service;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Service $iaService
    ) {
    }
    public static function getSubscribedEvents()
    {
        return [
            MessageCreatedEvent::NAME => 'onMessageCreated',
        ];
    }

    public function onMessageCreated(MessageCreatedEvent $event)
    {
        //$this->iaService->generateMessageContent($event->getMessageId());
    }
}