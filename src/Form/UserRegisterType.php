<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,['label'=>'Pseudo: '])
            ->add('firstName',TextType::class,['label'=>'Prénom: '])
            ->add('lastName',TextType::class,['label'=>'Nom: '])
            ->add('phone',TextType::class,['label'=>'Téléphone: '])
            ->add('mail', TextType::class,['label'=>'Email: '])
            ->add('password', PasswordType::class,['label'=>'motdepasse:'])
            ->add('admin', TextType::class,['label'=>'administrateur: '])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
