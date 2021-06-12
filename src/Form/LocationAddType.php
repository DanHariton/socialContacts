<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', CountryType::class, [
                'label' => 'Stat *',
                'required' => true,
                'preferred_choices' => [
                    'CZ', 'SK', 'UA'
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Mesto *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('streetName', TextType::class, [
                'label' => 'Ulice *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('streetNumber', TextType::class, [
                'label' => 'Cislo domu *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('zip', TextType::class, [
                'label' => 'PSC *',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}