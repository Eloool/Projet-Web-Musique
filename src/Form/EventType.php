<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Event;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Name')
            ->add('Date', null, [
                'widget' => 'single_text',
            ])
            ->add('Artist', EntityType::class, [
                'class' => Artiste::class,
                'choice_label' => 'name', // 🔥 Affiche le nom au lieu de l'ID
                'placeholder' => 'Sélectionnez un artiste', // Optionnel : ajoute une option vide
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'allow_extra_fields' => true, // Permet d'ignorer les champs non attendus
            'csrf_field_name' => '_token', // Nom du champ CSRF
            'csrf_token_id'   => 'event_item', // Identifiant du token (unique par formulaire)
        ]);
    }
}
