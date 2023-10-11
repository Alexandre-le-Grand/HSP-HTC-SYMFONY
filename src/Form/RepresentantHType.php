<?php

namespace App\Form;

use App\Entity\RepresentantH;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepresentantHType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('password')
            ->add('nom_hopital', TextType::class, [
                'label' => 'Nom de l\'hôpital',
            ])
            ->add('role', TextType::class, [
                'label' => 'Rôle dans l\'hôpital',
            ])
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RepresentantH::class,
        ]);
    }
}
