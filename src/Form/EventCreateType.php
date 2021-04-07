<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label'=> 'Nom de la sortie: '])
            ->add('eventDate', TextType::class, ['label'=> 'Date et heure de la sortie: '])
            ->add('limitDate', TextType::class, ['label'=> 'Date limite d\'inscription: '])
            ->add('nbrPlace', TextType::class, ['label'=> 'Nombre de places: '])
            ->add('duration', TextType::class, ['label'=> 'Durée: '])
            ->add('description', TextareaType::class, ['label'=> 'Description et infos: '])
            ->add('campus', EntityType::class,['class'=>Campus::class, 'choice_label'=>'name'])
            ->add('city', EntityType::class, ['class'=>Ville::class, 'choice_label'=>'name'])
            ->add('place', EntityType::class, ['class'=>Place::class, 'choice_label'=>'name'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
