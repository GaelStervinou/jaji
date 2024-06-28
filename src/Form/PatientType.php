<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ipp', null, [
                'attr' => [
                    'placeholder' => 'Renseigner l\'IPP...',
                ],
            ])
            ->add('firstname', null, [
                'attr' => [
                    'placeholder' => 'Ajouter le prénom...',
                ],
            ])
            ->add('lastname', null, [
                'attr' => [
                    'placeholder' => 'Ajouter le nom...',
                ],
            ])
            ->add('phone', null, [
                'attr' => [
                    'placeholder' => 'Ajouter le numéro de téléphone...',
                ],
            ])
            ->add('email', null, [
                'attr' => [
                    'placeholder' => 'Ajouter l\'adresse email...',
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'H',
                    'Femme' => 'F',
                    'Autre' => 'O',
                ],
                'attr' => [
                    'placeholder' => 'Ajouter le genre (H/F/O)...',
                ],
            ])
            ->add('birthDate', null, [
                'widget' => 'single_text',
            ])
            ->add('iaEnabled', null, [
                'attr' => [
                    'placeholder' => 'Est-ce que le patient accepte l\'IA ?...',
                ],
            ])
            ->add('observation', null, [
                'attr' => [
                    'placeholder' => 'Ajouter une observation...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
