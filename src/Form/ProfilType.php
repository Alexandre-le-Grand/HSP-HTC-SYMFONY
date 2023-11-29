<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $userRole = $options['user_role'];

        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prénom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('email', EmailType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Adresse mail :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]
            ]);

        if ($userRole === 'ROLE_ETUDIANT') {
            $builder
                ->add('domaineEtude', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Domaine d\'étude :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ]
                ]);
        } elseif ($userRole === 'ROLE_REPRESENTANT') {
            $builder
                ->add('nom_hopital', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Nom de l\'hôpital :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ]
                ])
                ->add('adresse', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Adresse de l\'hôpital :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ]
                ])
                ->add('role', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Vôtre rôle dans l\'hôpital :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ]
                ]);
        }

        $builder
            ->add('plainPassword', RepeatedType:: class, [
                'type' => PasswordType:: class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Mot de passe :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank()
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Confirmation du mot de passe :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas. '
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' =>'btn btn-primary mt-4'
                ],
                'label' => 'Modifier mon profil',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'user_role' => null,
        ]);
    }
}
