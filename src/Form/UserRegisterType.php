<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Pseudo: '])
            ->add('firstName', TextType::class, ['label' => 'Prénom: '])
            ->add('lastName', TextType::class, ['label' => 'Nom: '])
            ->add('phone', TextType::class, ['label' => 'Téléphone: '])
            ->add('mail', TextType::class, ['label' => 'Email: '])
            ->add('admin', TextType::class, ['label' => 'Administrateur: '])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe:',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un mot de passe.',
                    ]),
//                    new Length([
//                        'min' => 6,
//                        'minMessage' => 'Your password should be at least {{ limit }} characters',
//                        // max length allowed by Symfony for security reasons
//                        'max' => 4096,
//                    ]),
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
