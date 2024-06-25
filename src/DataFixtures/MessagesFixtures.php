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

        $messages = [
            "Je suis très satisfait des soins que j'ai reçus à l'hôpital. Le personnel était très attentionné.",
            "Merci à tous les médecins et infirmières pour leur professionnalisme et leur gentillesse.",
            "L'hôpital est très propre et bien organisé. Je me suis senti en sécurité pendant tout mon séjour.",
            "Les délais d'attente étaient un peu longs, mais la qualité des soins était excellente.",
            "Je me sens beaucoup mieux après mon traitement. Merci pour tout.",
            "Les infirmières étaient toujours disponibles pour répondre à mes questions. Un grand merci à elles.",
            "Je n'ai pas apprécié la nourriture à l'hôpital, mais les soins étaient très bons.",
            "Merci à l'équipe de chirurgie pour leur travail exceptionnel. Mon opération s'est très bien passée.",
            "Les chambres sont confortables et bien équipées. Je recommande cet hôpital.",
            "Je suis reconnaissant pour l'attention et les soins reçus pendant mon hospitalisation.",
            "Les médecins ont pris le temps de m'expliquer mon traitement en détail. J'ai beaucoup apprécié.",
            "Le personnel de l'hôpital est très professionnel et compétent. Merci à tous.",
            "Je suis très content de mon rétablissement. Merci à toute l'équipe médicale.",
            "Les soins reçus à l'hôpital ont été excellents. Je suis très satisfait.",
            "Les infirmières étaient très attentionnées et aimables. Merci à elles.",
            "L'hôpital est moderne et bien entretenu. Mon séjour a été très agréable.",
            "Je me sens beaucoup mieux après mon passage à l'hôpital. Merci pour votre aide.",
            "Les médecins et infirmières ont été formidables. Je suis très reconnaissant.",
            "Merci pour les soins de qualité et l'attention portée à mes besoins.",
            "L'équipe médicale a été très rassurante et professionnelle. Merci à tous.",
            "Je recommande cet hôpital pour la qualité des soins et la gentillesse du personnel.",
            "Les délais d'attente pour les consultations sont un peu longs, mais les soins sont excellents.",
            "Merci pour votre soutien et vos soins. Je me sens beaucoup mieux maintenant.",
            "Les installations de l'hôpital sont excellentes. Je suis très satisfait.",
            "Je me suis senti bien pris en charge pendant tout mon séjour à l'hôpital.",
            "Merci pour votre professionnalisme et votre dévouement. Vous faites un travail formidable.",
            "Les médecins sont très compétents et attentionnés. Merci pour tout.",
            "Je suis très satisfait des soins reçus. Merci à toute l'équipe médicale.",
            "L'hôpital est très bien organisé. Je me suis senti en sécurité pendant tout mon séjour.",
            "Merci pour les soins de qualité et l'attention portée à ma guérison.",
            "Je suis très content de mon rétablissement grâce aux soins reçus à l'hôpital.",
            "Les infirmières étaient toujours disponibles et très aimables. Merci pour tout.",
            "Les médecins ont pris le temps de répondre à toutes mes questions. Je suis très reconnaissant.",
            "Je me sens très seul et anxieux depuis ma sortie de l'hôpital. J'aurais aimé avoir plus de soutien psychologique.",
            "Je suis reconnaissant pour les soins, mais je me sens toujours très déprimé.",
            "Je n'arrive pas à surmonter cette sensation de tristesse depuis mon opération. Les soins étaient bons, mais mon moral est au plus bas.",
            "Merci pour les soins, mais je me sens encore très anxieux et perdu.",
            "Je suis satisfait des soins, mais j'ai du mal à gérer mon stress post-opératoire.",
            "Les médecins étaient compétents, mais je me sens toujours très déprimé après mon hospitalisation.",
            "Je me sens très isolé et triste depuis ma sortie. J'aurais apprécié plus de soutien émotionnel.",
            "Les soins médicaux étaient excellents, mais je n'ai reçu aucun soutien psychologique. Je me sens très seul.",
            "Je suis très reconnaissant pour les soins, mais mon anxiété est toujours très présente.",
            "Merci pour les soins, mais je lutte toujours contre une grande tristesse.",
            "Je suis content d'être guéri, mais je me sens toujours très déprimé.",
            "Les soins étaient bons, mais mon moral est très bas depuis ma sortie.",
            "Je me sens très anxieux et déprimé depuis mon séjour à l'hôpital.",
            "Merci pour les soins, mais je me sens encore très isolé et triste.",
            "Les soins médicaux étaient bons, mais mon état mental s'est détérioré.",
            "Je suis satisfait des soins, mais j'ai beaucoup de mal à gérer mon anxiété.",
            "Les médecins étaient très compétents, mais je me sens toujours très déprimé.",
            "Je suis reconnaissant pour les soins, mais mon moral est au plus bas.",
            "Les soins étaient excellents, mais je me sens très seul et anxieux."
        ];

        $faker = \Faker\Factory::create('fr_FR');
        foreach ($patients as $patient) {
            for ($i = 0; $i < 5; $i++) {
                $message = (new Messages())
                    ->setContent($faker->randomElement($messages))
                    ->setPatient($patient);
                $manager->persist($message);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PatientFixtures::class
        ];
    }
}
