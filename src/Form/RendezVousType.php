<?php

namespace App\Form;

use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', ChoiceType::class, [
                'choices' => $this->generateDateChoices(),
                'attr' => [
                    'class' => 'form-control select2',
                    'placeholder' => 'DD-MM-YYYY',
                    'pattern' => '[0-9]{2}-[0-9]{2}-[0-9]{4}',
                ],
                'label' => 'Date du rendez-vous :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('heure', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'HH:mm',
                    'pattern' => '[0-9]{2}:[0-9]{2}',
                ],
                'label' => 'Heure du rendez-vous :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' =>'btn btn-primary mt-4'
                ],
                'label' => 'Créer un rendez-vous',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }

    private function generateDateChoices(): array
    {
        $currentDate = new \DateTime();
        $endDate = (clone $currentDate)->modify('+2 years');

        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($currentDate, $interval, $endDate);

        $choices = [];
        foreach ($dateRange as $date) {
            $choices[$date->format('d-m-Y')] = $date->format('d-m-Y');
        }

        return $choices;
    }

}