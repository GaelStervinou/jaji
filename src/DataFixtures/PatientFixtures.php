<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PatientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        $observations = [
            "Antécédent familial: Historique de maladies cardiaques",
            "Antécédent familial: Diabète de type 2 dans la famille",
            "Antécédent familial: Cancer du sein chez la mère",
            "Antécédent familial: Hypertension artérielle",
            "Antécédent familial: Maladies auto-immunes",
            "Antécédent familial: Asthme dans la famille",
            "Antécédent familial: Sclérose en plaques chez un parent",
            "Antécédent familial: Maladie de Parkinson chez le grand-père",
            "Antécédent familial: Troubles de l'humeur et dépression dans la famille",
            "Antécédent familial: Arthrite rhumatoïde chez un proche",
            "Allergie: Antibiotiques (pénicilline)",
            "Allergie: Fruits de mer",
            "Allergie: Arachides",
            "Allergie: Latex",
            "Condition médicale: Asthme",
            "Condition médicale: Diabète de type 1",
            "Condition médicale: Hypertension",
            "Condition médicale: Hypercholestérolémie",
            "Condition médicale: Insuffisance rénale",
            "Condition médicale: Maladie cœliaque",
            "Condition médicale: Migraine",
            "Condition médicale: Epilepsie",
            "Condition médicale: Maladie de Crohn",
            "Antécédent chirurgical: Appendicectomie",
            "Antécédent chirurgical: Cholécystectomie",
            "Antécédent chirurgical: Remplacement de la hanche",
            "Antécédent chirurgical: Pontage coronarien",
            "Antécédent chirurgical: Prothèse de genou",
            "Habitude de vie: Fumeur",
            "Habitude de vie: Consommation d'alcool",
            "Habitude de vie: Sédentarité",
            "Habitude de vie: Alimentation déséquilibrée",
            "Habitude de vie: Activité physique régulière",
            "Symptôme: Douleur thoracique",
            "Symptôme: Essoufflement",
            "Symptôme: Fatigue chronique",
            "Symptôme: Douleurs articulaires",
            "Symptôme: Maux de tête fréquents",
            "Symptôme: Vertiges",
            "Symptôme: Nausées",
            "Symptôme: Insomnie",
            "Symptôme: Anxiété",
            "Symptôme: Dépression",
            "Symptôme: Perte de poids inexpliquée",
            "Symptôme: Gain de poids inexpliqué",
            "Symptôme: Palpitations",
            "Symptôme: Fièvre récurrente",
            "Symptôme: Eruptions cutanées",
            "Symptôme: Vision floue"
        ];

        for ($i = 0; $i < 1000; $i++) {
            $patient = (new Patient())
                ->setIpp($faker->unique()->isbn13)
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setPhone($faker->phoneNumber)
                ->setEmail($faker->email)
                ->setGender($faker->randomElement(['M', 'F', 'O']))
                ->setBirthdate($faker->dateTimeBetween('-100 years', '-1 years'))
                ->setIaEnabled($faker->boolean)
                ->setObservation(implode(", ",$faker->randomElements($observations, $faker->numberBetween(1, 5))))
            ;
            $manager->persist($patient);
        }

        $manager->flush();
    }
}
