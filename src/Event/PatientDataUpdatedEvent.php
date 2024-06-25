<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class PatientDataUpdatedEvent extends Event
{
    public function __construct(
        private int $patientId,
    ) {
    }

    public function getPatientId(): int
    {
        return $this->patientId;
    }
}