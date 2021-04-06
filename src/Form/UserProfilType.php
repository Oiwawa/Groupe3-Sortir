<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null,['label'=>'Pseudo: '])
            ->add('firstName',null,['label'=>'Prénom: '])
            ->add('lastName',null,['label'=>'Nom: '])
            ->add('phone',null,['label'=>'Téléphone: '])
            ->add('mail', null,['label'=>'Email: '])
            ->add('password',null,['label'=>'Mot de passe: '])
            ->add('password',null,['label'=>'Confirmation: '])
            ->add('campus',ChoiceType::class, ['choices' =>['Saint-Herblain'=>'Saint-Herblain',
                'Nantes'=>'Nantes', 'La-Roche-sur-Yon'=>'La-Roche-sur-Yon']])
//            ->add('Ma photo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
