<?php

namespace App\IA;

use App\Entity\Events;
use App\Entity\Messages;
use App\Entity\Patient;
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
    private string $observations;

    private function __construct(array $messages = [], array $events = [], array $weights = [], string $observations = '')
    {
        $this->messages = $messages;
        $this->events = $events;
        $this->weights = $weights;
        $this->observations = $observations;
    }

    public static function retrievePatientInformation(Patient $patient, EntityManagerInterface $entityManager): self
    {
        $messages = self::retrieveMessages($patient->getId(), $entityManager);
        $events = self::retrieveEvents($patient->getId(), $entityManager);
        $weights = self::retrieveWeights($patient->getId(), $entityManager);
        $observations = $patient->getObservation();

        return new self($messages, $events, $weights, $observations);
    }

    private static function retrieveMessages(int $patientId, EntityManagerInterface $entityManager): array
    {
        return (new ArrayCollection($entityManager->getRepository(Messages::class)->findBy(['patient' => $patientId], ['createdAt' => 'DESC'])))->map(function (Messages $message) {
            return [
                'content' => $message->getContent(),
                'type' => $message->getMedia(), // 'text', 'audio', 'image
                'date' => $message->getCreatedAt()?->format('Y-m-d H:i:s'),
                'path' => $message->getPath() ?? '',
            ];
        })->toArray();
    }

    private static function retrieveEvents(int $patientId, EntityManagerInterface $entityManager): array
    {
        return (new ArrayCollection($entityManager->getRepository(Events::class)->findBy(['patient' => $patientId], ['date' => 'DESC'])))->map(function (Events $event) {
            return [
                'description' => $event->getValue(),
                'type' => 'event',
                'date' => $event->getDate()?->format('Y-m-d H:i:s'),
            ];
        })->toArray();
    }

    private static function retrieveWeights(int $patientId, EntityManagerInterface $entityManager): array
    {
        return (new ArrayCollection($entityManager->getRepository(Weights::class)->findBy(['patient' => $patientId], ['date' => 'DESC'])))->map(function (Weights $weight) {
            return [
                'weight' => $weight->getValue(),
                'type' => 'weight',
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
            'observations' => $this->observations,
        ], JSON_THROW_ON_ERROR);
    }
}