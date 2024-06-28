<?php

namespace App\Form;

use App\Entity\Events;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', null, [
                'attr' => [
                    'placeholder' => 'Ajouter la valeur...',
                ],
            ])
            ->add('type', null, [
                'attr' => [
                    'placeholder' => 'Ajouter le type d\'événement...',
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
            'data_class' => Events::class,
        ]);
    }
}
