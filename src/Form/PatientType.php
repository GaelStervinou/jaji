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
            ->add('ipp')
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('email')
            ->add('gender')
            ->add('birthDate', null, [
                'widget' => 'single_text',
            ])
            ->add('iaEnabled')
            ->add('observation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
