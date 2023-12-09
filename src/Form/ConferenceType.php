<?php

namespace App\Form;

use App\Entity\Conference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ConferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nom de la conférence :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('date', ChoiceType::class, [
                'choices' => $this->generateDateChoices(),
                'attr' => [
                    'class' => 'form-control select2',
                ],
                'label' => 'Date de la conférence :\n',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ])
            ->add('heure', ChoiceType::class, [
                'choices' => $this->generateHeureChoices(),
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Heure de la conférence :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez sélectionner l\'heure de la conférence.',
                    ]),
                    new Callback([$this, 'validateHeure']),
                ],
            ])
            ->add('duree', ChoiceType::class, [
                'choices' => $this->generateDureeChoices(),
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Durée de la conférence :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez sélectionner la durée de la conférence.',
                    ]),
                    new Callback([$this, 'validateDuree']),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label'  => $options['is_edit'] ? 'Modifier la conférence' : 'Créer la conférence',
            ]);
    }

    private function generateDateChoices()
    {
        $startDate = new \DateTime('tomorrow');
        $endDate = new \DateTime('+2 years');

        $interval = new \DateInterval('P1D'); // Intervalle d'un jour
        $dates = [];

        while ($startDate <= $endDate) {
            // Vérifier si la date est un jour de la semaine (mardi à samedi)
            if ($startDate->format('N') >= 2 && $startDate->format('N') <= 6) {
                $dateString = $startDate->format('d-m-Y');
                $dates[$dateString] = $dateString;
            }
            $startDate->add($interval);
        }

        return $dates;
    }

    private function generateHeureChoices()
    {
        $choices = [];
        $heureDebut = 8;
        $heureFin = 11;

        for ($heure = $heureDebut; $heure <= $heureFin; $heure++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $formattedTime = sprintf('%02d:%02d', $heure, $minute);
                $choices[$formattedTime] = $formattedTime;
            }
        }

        return $choices;
    }

    public function validateHeure($heure, ExecutionContextInterface $context)
    {
        if (strtotime($heure) < strtotime('08:00')) {
            $context
                ->buildViolation('L\'heure de la conférence doit être au moins 08:00.')
                ->atPath('heure')
                ->addViolation();
        }
    }


    private function generateDureeChoices()
    {
        $choices = [];
        $heureMax = 4;

        for ($heure = 0; $heure <= $heureMax; $heure++) {
            for ($minute = ($heure === 0 ? 30 : 0); $minute < 60; $minute += 30) {
                if ($heure === $heureMax && $minute === 30) {
                    break;
                }

                $formattedDuree = sprintf('%02d:%02d', $heure, $minute);
                $choices[$formattedDuree] = $formattedDuree;
            }
        }

        return $choices;
    }


    public function validateDuree($duree, ExecutionContextInterface $context)
    {
        // Vous pouvez récupérer l'heure de début ici
        $heureDebut = $context->getRoot()->get('heure')->getData();

        // Assurez-vous que l'heure de début est au format HH:mm
        if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $heureDebut)) {
            return; // Ne pas valider si l'heure de début est au mauvais format
        }

        // Convertissez la durée en minutes pour la comparer
        $dureeMinutes = intval(substr($duree, 0, 2)) * 60 + intval(substr($duree, 3, 2));

        // L'heure de début + durée ne doit pas dépasser 12:00
        $heureDebutMinutes = intval(substr($heureDebut, 0, 2)) * 60 + intval(substr($heureDebut, 3, 2));
        $heureMaxMinutes = intval(substr('12:00', 0, 2)) * 60 + intval(substr('12:00', 3, 2));

        if (($heureDebutMinutes + $dureeMinutes) > $heureMaxMinutes) {
            $context
                ->buildViolation('La durée de la conférence dépasse 12h00.')
                ->atPath('duree')
                ->addViolation();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conference::class,
            'is_edit' => false, // valeur par défaut de l'option is_edit
        ]);
    }
}
