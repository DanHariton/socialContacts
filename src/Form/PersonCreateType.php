<?php

namespace App\Form;

use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PersonCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Prijemni *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [new NotBlank()]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Jmeno *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [new NotBlank()]
            ])
            ->add('nickname', TextType::class, [
                'label' => 'Username *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [new NotBlank()]
            ])
            ->add('birthDay', DateType::class, [
                'label' => 'Datum narozeni *',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Pohlavi',
                'required' => false,
                'choices' => [
                    'Muz' => Person::GENDER_MALE,
                    'Zena' => Person::GENDER_FEMALE,
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('height', TextType::class, [
                'label' => 'Vyska',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('location', LocationAddType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'mt-2'
                ],
            ])
            ->add('contacts', CollectionType::class, [
                'label' => false,
                'entry_type' => ContactAddType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add('sumbmit', SubmitType::class, [
                'label' => 'Vytvorit',
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}