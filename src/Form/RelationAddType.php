<?php

namespace App\Form;

use App\Entity\Relation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelationAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $personChoices = [];
        $relationChoices = [];

        foreach ($options['persons'] as $person) {
            $personChoices[$person->getLastName() . ' ' . $person->getFirstName()] = $person->getId();
        }

        foreach ($options['relationTypes'] as $relationType) {
            $relationChoices[$relationType->getName()] = $relationType->getId();
        }

        $builder
            ->add('person2', ChoiceType::class, [
                'label' => '..z kym',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => $personChoices
            ])
            ->add('relation', ChoiceType::class, [
                'label' => 'Typ vztahu',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => $relationChoices
            ])
            ->add('sumbmit', SubmitType::class, [
                'label' => 'Pridat vztah',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Relation::class,
            'relationTypes' => null,
            'persons' => null
        ]);
    }
}