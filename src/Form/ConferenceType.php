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
            ->add('heure', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'HH:mm',
                ],
                'label' => 'Heure de la conférence :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/',
                        'message' => 'L\'heure de la conférence doit être au format HH:mm (par exemple, 08:00).',
                    ]),
                    new Assert\GreaterThanOrEqual([
                        'value' => '08:00',
                        'message' => 'L\'heure de la conférence doit être au moins 08:00.',
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => '11:30',
                        'message' => 'L\'heure de la conférence ne peut pas être après 11:30.',
                    ]),
                ],
            ])
            ->add('duree', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'HH:mm',
                ],
                'label' => 'Durée de la conférence :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d{2}:\d{2}$/',
                        'message' => 'Le format de la durée doit être hh:mm (par exemple, 02:30 pour 2 heures et 30 minutes).',
                    ]),
                    new Assert\Callback([$this, 'validateDuree']),
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
            'is_edit' => false, // Ajoutez cette ligne pour définir la valeur par défaut de l'option is_edit
        ]);
    }
}
