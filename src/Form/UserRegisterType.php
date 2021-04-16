<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo: '
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom: '
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom: '
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone: '
            ])
            ->add('mail', TextType::class, [
                'label' => 'Email: '
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name'
            ])
            ->add('admin', CheckboxType::class, [
                'label' => 'Administrateur ',
                'required' => false
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe:',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un mot de passe.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
