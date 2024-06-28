<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\Weights;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeightsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', null, [
                'attr' => [
                    'placeholder' => 'Enter the weight value...',
                ],
            ])
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Weights::class,
        ]);
    }
}
