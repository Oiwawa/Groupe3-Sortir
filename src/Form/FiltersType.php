<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\EventState;
use App\Filters\Filters;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Le nom de la sortie contient: ',
                'required' => false,
                'attr' => ['placeholder' => 'Search'
                ]])
            ->add('campus', EntityType::class, [
                'label' => false,
                'choice_label' => 'name',
                'placeholder' => 'Campus',
                'required' => false,
                'class' => Campus::class,
                // 'expanded' => true,
                //   'multiple' => true
            ])
            ->add('state', EntityType::class, [
                'label' => false,
                'choice_label' => 'state',
                'required' => false,
                'class' => EventState::class,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('dateStart', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('search', SubmitType::class, [
                'attr' => ['value' => 1],
                'label' => 'Rechercher'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filters::class,
            'csrf_protection' => false
        ]);
    }
}
