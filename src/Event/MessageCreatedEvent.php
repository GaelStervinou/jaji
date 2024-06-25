<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class MessageCreatedEvent extends Event
{
    public const NAME = 'message.created';


    public function __construct(private int $messageId)
    {
    }

    public function getMessageId(): int
    {
        return $this->messageId;
    }
}