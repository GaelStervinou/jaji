<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 13)]
    private ?string $ipp = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 1)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column]
    private ?bool $iaEnabled = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observation = null;

    /**
     * @var Collection<int, Events>
     */
    #[ORM\OneToMany(targetEntity: Events::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $events;

    /**
     * @var Collection<int, Weights>
     */
    #[ORM\OneToMany(targetEntity: Weights::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $weights;

    /**
     * @var Collection<int, DiagnosticMentalHealth>
     */
    #[ORM\OneToMany(targetEntity: DiagnosticMentalHealth::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $diagnosticMentalHealth;

    /**
     * @var Collection<int, DiagnosticRisks>
     */
    #[ORM\OneToMany(targetEntity: DiagnosticRisks::class, mappedBy: 'patient', orphanRemoval: true)]
    private Collection $diagnosticRisks;

    private ?int $lastDiagnosticMentalHealthScore = null;
    private ?int $lastDiagnosticRisksScore = null;

    private ?DiagnosticMentalHealth $lastDiagnoticMentalHealth = null;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->weights = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIpp(): ?string
    {
        return $this->ipp;
    }

    public function setIpp(string $ipp): static
    {
        $this->ipp = $ipp;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function isIaEnabled(): ?bool
    {
        return $this->iaEnabled;
    }

    public function setIaEnabled(bool $iaEnabled): static
    {
        $this->iaEnabled = $iaEnabled;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(?string $observation): static
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * @return Collection<int, Events>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setPatient($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getPatient() === $this) {
                $event->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Weights>
     */
    public function getWeights(): Collection
    {
        return $this->weights;
    }

    public function addWeight(Weights $weight): static
    {
        if (!$this->weights->contains($weight)) {
            $this->weights->add($weight);
            $weight->setPatient($this);
        }

        return $this;
    }

    public function removeWeight(Weights $weight): static
    {
        if ($this->weights->removeElement($weight)) {
            // set the owning side to null (unless already changed)
            if ($weight->getPatient() === $this) {
                $weight->setPatient(null);
            }
        }

        return $this;
    }

    public function getLastDiagnosticMentalHealthScore(): ?int
    {
        $iterator = $this->getDiagnosticMentalHealth()->getIterator();
        $iterator->uasort(function (DiagnosticMentalHealth $a, DiagnosticMentalHealth $b) {
            return ($a->getCreatedAt() < $b->getCreatedAt()) ? -1 : 1;
        });
        $collection = new ArrayCollection(iterator_to_array($iterator));
        return $collection->last()?->getValue();
    }

    public function setLastDiagnosticMentalHealthScore(int $lastDiagnosticMentalHealthScore): static
    {
        $this->lastDiagnosticMentalHealthScore = $lastDiagnosticMentalHealthScore;

        return $this;
    }

    public function getLastDiagnosticRisksScore(): ?int
    {
        $iterator = $this->getDiagnosticsRisks()->getIterator();
        $iterator->uasort(function (DiagnosticRisks $a, DiagnosticRisks $b) {
            return ($a->getCreatedAt() < $b->getCreatedAt()) ? -1 : 1;
        });
        $collection = new ArrayCollection(iterator_to_array($iterator));
        return $collection->last()?->getValue();
    }

    public function setLastDiagnosticRisksScore(int $lastDiagnosticRisksScore): static
    {
        $this->lastDiagnosticRisksScore = $lastDiagnosticRisksScore;

        return $this;
    }

    /**
     * @return Collection<int, DiagnosticMentalHealth>
     */
    public function getDiagnosticMentalHealth(): Collection
    {
        return $this->diagnosticMentalHealth;
    }

    /**
     * @return Collection<int, DiagnosticRisks>
     */
    public function getDiagnosticsRisks(): Collection
    {
        return $this->diagnosticRisks;
    }

    public function getLastDiagnoticMentalHealth(): ?DiagnosticMentalHealth
    {
        $diagnostics = $this->getDiagnosticMentalHealth()->toArray();

        usort($diagnostics, function(DiagnosticMentalHealth $a, DiagnosticMentalHealth $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });

        return $diagnostics[0] ?? null;
    }

    public function setLastDiagnoticMentalHealth(DiagnosticMentalHealth $lastDiagnoticMentalHealth): static
    {
        $this->lastDiagnoticMentalHealth = $lastDiagnoticMentalHealth;

        return $this;
    }
}
