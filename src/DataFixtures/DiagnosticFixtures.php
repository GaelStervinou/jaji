<?php

namespace App\DataFixtures;

use App\DataFixtures\PatientFixtures;
use App\Entity\DiagnosticMentalHealth;
use App\Entity\DiagnosticRisks;
use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DiagnosticFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $patients = $manager->getRepository(Patient::class)->findAll();

        $mentalHealthFakeValues = [
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
        $mentalHealthFakeReasons = [
            json_encode([
                "title" => "Dépression",
                "conclusion" => "Le patient semble se sentir triste et désespéré selon ses propres mots.",
                "source" => "Je suis triste et désespéré",
                "type" => "audio",
                "severity" => 3,
                "path" => "https://samples.audible.fr/bk/adfr/002605/bk_adfr_002605_sample.mp3"
            ], JSON_THROW_ON_ERROR),
            json_encode([
                "title" => "Pensées suicidaires",
                "conclusion" => "Le patient semble avoir des pensées suicidaires.",
                "source" => "Cette image montre une corde accrochée au plafond au-dessus d'un tabouret.",
                "type" => "image",
                "severity" => 3,
                "path" => "https://t.ly/PbjJ_"
            ], JSON_THROW_ON_ERROR),
            json_encode([
                "title" => "Fatigue",
                "conclusion" => "Le patient mentionne se sentir très fatigué.",
                "source" => "Je me sens très fatigué",
                "type" => "audio",
                "severity" => 2,
                "path" => "https://samples.audible.fr/bk/adfr/002605/bk_adfr_002605_sample.mp3"
            ], JSON_THROW_ON_ERROR)
        ];

        $risksFakeValues = [
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
        $risksFakeReasons = [
            json_encode([
                "title" => "Hypocalcémie post-thyroïdectomie",
                "conclusion" => "Le patient a développé une hypocalcémie après la thyroïdectomie, nécessitant une surveillance étroite.",
                "source" => "Thyroïdectomie : Le patient souffrant de nodules thyroïdiens a subi une thyroïdectomie. Le patient a développé une hypocalcémie après la chirurgie, nécessitant une supplémentation en calcium et une surveillance étroite.",
                "type" => "event",
                "severity" => 3
            ] , JSON_THROW_ON_ERROR),
            json_encode([
                "title" => "Plaie infectée",
                "conclusion" => "Le patient présente une plaie infectée nécessitant une attention médicale immédiate.",
                "source" => "Cette image montre une plaie infectée.",
                "type" => "image",
                "severity" => 2,
                "path" => "https://t.ly/7HE_s"
            ], JSON_THROW_ON_ERROR),
        ];

        $faker = Factory::create('fr_FR');

        foreach ($patients as $patient) {
            for ($i = 0; $i < 3; $i++) {
                $currentDate = new \DateTimeImmutable($faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d H:i:s'));
                $randomReasonsNumber = $faker->numberBetween(1, 2);
                $diagnosticMentalHealth = (new DiagnosticMentalHealth())
                    ->setValue($faker->numberBetween(0, 10))
                    ->setContent($faker->randomElement($mentalHealthFakeValues))
                    ->setReasons("[".implode(",", $faker->randomElements($mentalHealthFakeReasons, $randomReasonsNumber))."]")
                    ->setPatient($patient)
                    ->setCreatedAt($currentDate)
                ;
                $diagnosticRisks = (new DiagnosticRisks())
                    ->setValue($faker->numberBetween(0, 10))
                    ->setContent($faker->randomElement($risksFakeValues))
                    ->setReasons("[".implode(",", $faker->randomElements($risksFakeReasons, $randomReasonsNumber))."]")
                    ->setPatient($patient)
                    ->setCreatedAt($currentDate)
                ;
                $manager->persist($diagnosticMentalHealth);
                $manager->persist($diagnosticRisks);
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