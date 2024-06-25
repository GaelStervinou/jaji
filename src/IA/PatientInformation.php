<?php

namespace App\IA;

use App\Entity\Events;
use App\Entity\Messages;
use App\Entity\Weights;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class PatientInformation
{
    /** @var $messages Messages[] */
    private array $messages;
    /** @var $events Events[] */
    private array $events;
    /** @var $weights Weights[] */
    private array $weights;

    private function __construct(array $messages = [], array $events = [], array $weights = [])
    {
        $this->messages = $messages;
        $this->events = $events;
        $this->weights = $weights;
    }

    public static function retrievePatientInformation(int $patientId, EntityManagerInterface $entityManager): self
    {
        $messages = self::retrieveMessages($patientId, $entityManager);
        $events = self::retrieveEvents($patientId, $entityManager);
        $weights = self::retrieveWeights($patientId, $entityManager);

        return new self($messages, $events, $weights);
    }

    private static function retrieveMessages(int $patientId, EntityManagerInterface $entityManager): array
    {
        return (new ArrayCollection($entityManager->getRepository(Messages::class)->findBy(['patient' => $patientId])))->map(function (Messages $message) {
            return $message->getContent();
        })->toArray();
    }

    private static function retrieveEvents(int $patientId, EntityManagerInterface $entityManager): array
    {
        return (new ArrayCollection($entityManager->getRepository(Events::class)->findBy(['patient' => $patientId])))->map(function (Events $event) {
            return $event->getValue();
        })->toArray();
    }

    private static function retrieveWeights(int $patientId, EntityManagerInterface $entityManager): array
    {
        return (new ArrayCollection($entityManager->getRepository(Weights::class)->findBy(['patient' => $patientId])))->map(function (Weights $weight) {
            return [
                'weight' => $weight->getValue(),
                'date' => $weight->getDate()?->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }

    public function toJson(): string
    {
        return json_encode([
            'messages' => $this->messages,
            'events' => $this->events,
            'weights' => $this->weights,
        ], JSON_THROW_ON_ERROR);
    }
}