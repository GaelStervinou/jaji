<?php

namespace App\DataFixtures;

use App\Entity\DiagnosticMentalHealth;
use App\Entity\DiagnosticRisks;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DiagnosticRisksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $patients = $manager->getRepository(Patient::class)->findAll();

        $fakeValues = [
            "Risque élevé de rechute de dépression majeure dû à l'arrêt des médicaments et à un stress récent.",
            "Risque modéré de crise d'angoisse généralisée suite à une situation de conflit familial.",
            "Risque de rechute de trouble respiratoire du sommeil en raison d'une prise de poids significative.",
            "Risque de complications postopératoires suite à une chirurgie cardiaque à haut risque.",
            "Risque de blessure auto-infligée en raison de pensées suicidaires récurrentes.",
            "Risque de rechute de trouble bipolaire en raison d'un manque de sommeil et d'une consommation d'alcool.",
            "Risque de rechute de trouble de la personnalité limite en raison d'une relation conflictuelle.",
            "Risque de rechute de trouble de l'alimentation en raison d'une perte de contrôle alimentaire.",
            "Risque de rechute de trouble de la consommation de substances en raison d'une exposition à des déclencheurs.",
            "Risque de crise de panique en raison d'une exposition à des situations phobiques.",
        ];

        $fakeReasons = [
            "Antécédents personnels de troubles de santé mentale.",
            "Antécédents familiaux de troubles de santé mentale.",
            "Événements de vie stressants ou traumatisants.",
            "Problèmes de santé physique ou mentale sous-jacents.",
            "Utilisation de substances psychoactives ou de médicaments.",
            "Facteurs environnementaux ou sociaux défavorables.",
            "Troubles neurodéveloppementaux ou génétiques.",
            "Traumatisme crânien ou lésion cérébrale.",
            "Maltraitance ou négligence dans l'enfance.",
            "Isolation sociale ou manque de soutien.",
        ];

        $faker = \Faker\Factory::create('fr_FR');

        foreach ($patients as $patient) {
            $diagnosticMentalHealth = (new DiagnosticRisks())
                ->setValue($faker->numberBetween(0, 10))
                ->setContent($faker->randomElement($fakeValues))
                ->setReasons($faker->randomElement($fakeReasons))
                ->setPatient($patient)
                ->setCreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d H:i:s')))
            ;
            $manager->persist($diagnosticMentalHealth);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PatientFixtures::class,
        ];
    }

}