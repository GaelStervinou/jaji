<?php

namespace App\DataFixtures;

use App\Entity\Events;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $patients = $manager->getRepository(Patient::class)->findAll();

        $fakeDescriptions = [
            "Chirurgies abdominales" => [
                "Appendicectomie : Le patient a subi une appendicectomie d'urgence après avoir présenté de fortes douleurs abdominales et de la fièvre. Après la chirurgie, il a développé une infection de la plaie qui a nécessité des antibiotiques supplémentaires et un court séjour à l'hôpital.",
                "Cholécystectomie laparoscopique : Par la suite, il a développé une fuite biliaire, causant une douleur significative et nécessitant une intervention supplémentaire pour la résoudre.",
                "Chirurgie de pontage gastrique : Le patient, qui souffrait d'obésité, a subi une chirurgie de pontage gastrique. Après la chirurgie, le patient a connu une fuite au site chirurgical, nécessitant une autre opération pour réparer.",
                "Réparation de hernie : Le patient a subi une intervention chirurgicale pour réparer une hernie inguinale. Le patient a développé une douleur postopératoire chronique, nécessitant une gestion de la douleur et une thérapie physique.",
                "Transplantation du foie : Malheureusement, le patient a développé un rejet aigu, nécessitant une thérapie immunosuppressive intensive.",
                "Césarienne : Le patient a subi une césarienne en raison de complications pendant le travail. Le patient a souffert d'une infection de la plaie qui a prolongé son séjour à l'hôpital.",
            ],
            "Chirurgies cardiovasculaires et thoraciques" => [
                "Pontage aorto-coronarien (PAC) : Bien que la chirurgie ait initialement réussi, le patient a développé une fibrillation auriculaire, nécessitant des médicaments et une surveillance étroite.",
                "Implantation de stimulateur cardiaque : Le patient souffrant d'arythmie a reçu un stimulateur cardiaque. Peu de temps après l'intervention, le patient a souffert d'un déplacement de l'électrode, nécessitant une chirurgie de révision.",
            ],
            "Chirurgies orthopédiques" => [
                "Remplacement total de la hanche : Le patient a souffert d'une dislocation de la nouvelle articulation de la hanche deux semaines après la chirurgie, nécessitant une procédure de révision.",
                "Arthroscopie du genou : Le patient a subi une arthroscopie du genou pour réparer un ménisque déchiré. Malheureusement, le patient a développé un caillot de sang dans la jambe, nécessitant un traitement supplémentaire avec des anticoagulants.",
                "Chirurgie de fusion spinale : Le patient souffrant de maladie dégénérative des disques a subi une chirurgie de fusion spinale. Après l'opération, le patient a souffert de lésions nerveuses, nécessitant une rééducation prolongée.",
            ],
            "Chirurgies ORL (oreille, nez et gorge)" => [
                "Amygdalectomie : Le patient a subi une amygdalectomie en raison d'infections récurrentes. Il a souffert d'un saignement postopératoire, nécessitant un retour urgent en salle d'opération.",
                "Rhinoplastie : Le patient a subi une rhinoplastie pour des raisons à la fois esthétiques et fonctionnelles. Le patient a développé une infection postopératoire, nécessitant des antibiotiques supplémentaires et des soins de suivi.",
            ],
            "Chirurgie ophtalmique" => [
                "Chirurgie de la cataracte : Le patient a subi une chirurgie de la cataracte pour améliorer sa vision. Après l'opération, le patient a développé une infection à l'œil, nécessitant un traitement antibiotique intensif.",
            ],
            "Chirurgie endocrinienne" => [
                "Thyroïdectomie : Le patient souffrant de nodules thyroïdiens a subi une thyroïdectomie. Le patient a développé une hypocalcémie après la chirurgie, nécessitant une supplémentation en calcium et une surveillance étroite.",
            ],
        ];

        $faker = \Faker\Factory::create('fr_FR');

        foreach ($patients as $patient) {
            $events = $faker->randomElements($fakeDescriptions, $faker->numberBetween(1, 3));
            foreach ($events as $type => $event) {
                $event = (new Events())
                    ->setDate($faker->dateTimeBetween('-10 years', 'now'))
                    ->setValue($faker->randomElement($event))
                    ->setType($type)
                    ->setPatient($patient)
                ;
                $manager->persist($event);
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
