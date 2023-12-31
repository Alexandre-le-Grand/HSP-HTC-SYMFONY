<?php

namespace App\Form;

use App\Entity\Amphitheatre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class AmphitheatreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom de l\'amphithéâtre',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('nb_places', IntegerType::class, [
                'attr' =>  [
                    'class'=> 'form-control'
                ],
                'label' => 'Nombre de places',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive()
                ]
            ])
            ->add('submit', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary mt-4'
                ],
                'label'  => $options['is_edit'] ? 'Modifier l\'amphithéâtre' : 'Créer l\'amphithéâtre'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Amphitheatre::class,
            'is_edit' => false,
        ]);
    }
}
