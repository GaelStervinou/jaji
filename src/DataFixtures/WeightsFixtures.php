<?php

namespace App\DataFixtures;

use App\Entity\Events;
use App\Entity\Patient;
use App\Entity\Weights;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WeightsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $patients = $manager->getRepository(Patient::class)->findAll();

        $faker = \Faker\Factory::create('fr_FR');

        foreach ($patients as $patient) {
            $randomTimes = $faker->numberBetween(1, 3);
            for ($i = 0; $i < $randomTimes; $i++) {
                $weight = (new Weights())
                    ->setDate($faker->dateTimeBetween('-10 years', 'now'))
                    ->setValue($faker->randomFloat(2, 30, 200))
                    ->setPatient($patient);
                $manager->persist($weight);
            }
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
