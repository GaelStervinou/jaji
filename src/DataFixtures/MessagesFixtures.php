<?php

namespace App\DataFixtures;

use App\Entity\Messages;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MessagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $patients = $manager->getRepository(Patient::class)->findAll();

        $faker = \Faker\Factory::create('fr_FR');
        foreach ($patients as $patient) {
            for ($i = 0; $i < 5; $i++) {
                $message = (new Messages())
                    ->setContent($faker->realText(200))
                    ->setPatient($patient);
                $manager->persist($message);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            PatientFixtures::class
        ];
    }
}
