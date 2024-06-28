<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ipp', null, [
                'attr' => [
                    'placeholder' => 'Enter the IPP...',
                ],
            ])
            ->add('firstname', null, [
                'attr' => [
                    'placeholder' => 'Enter the firstname...',
                ],
            ])
            ->add('lastname', null, [
                'attr' => [
                    'placeholder' => 'Enter the lastname...',
                ],
            ])
            ->add('phone', null, [
                'attr' => [
                    'placeholder' => 'Enter the phone number...',
                ],
            ])
            ->add('email', null, [
                'attr' => [
                    'placeholder' => 'Enter the email...',
                ],
            ])
            ->add('gender', null, [
                'attr' => [
                    'placeholder' => 'Enter the gender... (M/F/X)',
                ],
            ])
            ->add('birthDate', null, [
                'widget' => 'single_text',
            ])
            ->add('iaEnabled', null, [
                'attr' => [
                    'placeholder' => 'Do the patient accept the IA usage...',
                ],
            ])
            ->add('observation', null, [
                'attr' => [
                    'placeholder' => 'Enter the observation...',
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
