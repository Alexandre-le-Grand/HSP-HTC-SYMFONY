<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\RepresentantH;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $utilisateur = $event->getData();
            $form = $event->getForm();

            if ($utilisateur instanceof Etudiant) {
                $form->add('domaineEtude', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Domaine d\'étude :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'required' => false,
                ]);
            }

            if ($utilisateur instanceof RepresentantH) {
                $form->add('nomHopital', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Nom de l\'hôpital :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'required' => false,
                ]);

                $form->add('adresseHopital', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Adresse de l\'hôpital :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'required' => false,
                ]);

                $form->add('roleRepresentant', TextType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Rôle du représentant :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'required' => false,
                ]);
            }
        })

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
            ])
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
            ->add('roles',ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Étudiant' => 'ROLE_ETUDIANT',
                    'Représentant' => 'ROLE_REPRESENTANT',
                ],
                'label' => 'Rôle :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' =>'btn btn-primary mt-4'
                ],
                'label' => 'Créer un nouvel utilisateur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
