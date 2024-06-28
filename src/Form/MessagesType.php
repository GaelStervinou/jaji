<?php

namespace App\Form;

use App\Entity\MessageMedia;
use App\Entity\Messages;
use App\Entity\Patient;
use App\Form\DataTransformer\MessageMediaToStringTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('media', ChoiceType::class, [
                'choices' => [
                    'Texte' => MessageMedia::TEXT,
                    'Image' => MessageMedia::IMAGE,
                    'Audio' => MessageMedia::AUDIO,
                ],
                'expanded' => true,
                'data' => MessageMedia::TEXT,
            ])
            ->add('content', null, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajouter le contenu du message...',
                ],
            ])
            ->add('subject', null, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ajouter le sujet du message...',
                ],
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['audio/mpeg', 'image/png', 'image/jpeg'],
                        'mimeTypesMessage' => 'Ajouter un fichier valide (audio ou image)',
                    ])
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => function(Patient $patient) {
                    return $patient->getFirstname() . ' ' . $patient->getLastname();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
