<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantMeetingAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $personsChoices = [];

        foreach ($options['persons'] as $person) {
            $personsChoices[$person->getLastName() . ' ' . $person->getFirstName()] = $person->getId();
        }

        $builder
            ->add('person', ChoiceType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'choices' => $personsChoices
            ])
            ->add('sumbmit', SubmitType::class, [
                'label' => 'Pridat',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'persons' => null,
        ]);
    }
}