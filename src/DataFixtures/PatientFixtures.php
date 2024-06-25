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
                ->setObservation($faker->text)
            ;
            $manager->persist($patient);
        }

        $manager->flush();
    }
}
