<?php

namespace App\DataFixtures;

use App\Entity\DiagnosticMentalHealth;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DiagnosticMentalHealthFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $patients = $manager->getRepository(Patient::class)->findAll();

        $fakeValues = [
            "Dépression majeure due à une perte d'intérêt pour les activités et une fatigue persistante depuis plusieurs mois.",
            "Trouble bipolaire de type 1 avec des épisodes maniaques et dépressifs.",
            "Trouble anxieux généralisé avec une préoccupation excessive et incontrôlable.",
            "Trouble obsessionnel-compulsif avec des pensées intrusives et des comportements répétitifs.",
            "Trouble de stress post-traumatique suite à un événement traumatisant.",
            "Schizophrénie paranoïde avec des hallucinations auditives et des idées délirantes.",
            "Trouble de la personnalité limite avec des relations instables et une impulsivité.",
            "Trouble de l'alimentation avec des comportements restrictifs et des préoccupations excessives sur le poids.",
            "Trouble de la consommation de substances avec une dépendance à l'alcool et aux drogues.",
            "Trouble du spectre autistique avec des difficultés de communication et des comportements répétitifs.",
        ];

        $fakeReasons = [
            "Antécédents familiaux de troubles mentaux.",
            "Événements de vie stressants ou traumatisants.",
            "Problèmes de santé physique ou mentale sous-jacents.",
            "Utilisation de substances psychoactives ou de médicaments.",
            "Facteurs environnementaux ou sociaux défavorables.",
            "Troubles neurodéveloppementaux ou génétiques.",
            "Traumatisme crânien ou lésion cérébrale.",
            "Maltraitance ou négligence dans l'enfance.",
            "Isolation sociale ou manque de soutien.",
            "Stigmatisation ou discrimination liée à la santé mentale.",
        ];

        $faker = \Faker\Factory::create('fr_FR');

        foreach ($patients as $patient) {
            $diagnosticMentalHealth = (new DiagnosticMentalHealth())
                ->setValue($faker->randomElement($fakeValues))
                ->setReasons($faker->randomElement($fakeReasons))
                ->setPatient($patient)
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