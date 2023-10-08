<?php

namespace App\Form;

use App\Entity\Conference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class ConferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('date', DateType::class, [
                    'widget' => 'choice',
                    'input'  => 'datetime_immutable',
                    'attr' => [
                        'class' => 'form-control'
                    ],
            ])
            ->add('heure', TimeType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable',
                'data' => new \DateTimeImmutable(),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('duree', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Durée (hh:mm)',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^\d{2}:\d{2}$/',
                        'message' => 'Le format de la durée doit être hh:mm (par exemple, 02:30 pour 2 heures et 30 minutes).',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Créer la conférence'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conference::class,
        ]);
    }
}
