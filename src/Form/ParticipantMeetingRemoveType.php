<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantMeetingRemoveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $participantChoices = [];

        foreach ($options['participants'] as $participant) {
            $participantChoices[$participant->getLastName() . ' ' . $participant->getFirstName()] = $participant->getId();
        }

        $builder
            ->add('person', ChoiceType::class, [
                'label' => false,
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'choices' => $participantChoices
            ])
            ->add('sumbmit', SubmitType::class, [
                'label' => 'Odstranit',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'participants' => null,
        ]);
    }
}