<?php

namespace App\Event;

use App\IA\Service;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PatientDataUpdatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Service $iaService
    ) {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            PatientDataUpdatedEvent::class => 'onPatientDataUpdated',
        ];
    }

    public function onPatientDataUpdated(PatientDataUpdatedEvent $event): void
    {
        $this->iaService->generateDiagnostics($event->getPatientId());
    }
}