<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ],
                    'label' => 'Username',
                    'constraints' => [
                        new Length([
                            'min' => 4,
                            'minMessage' => 'Le nom d\'utilisateur doit faire au moins {{ limit }} caractères',
                            'max' => 100,
                            'maxMessage' => 'Le mot de passe doit faire moins de {{ limit }} caractères',
                        ]),
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ],
                    'label' => 'Email',
                    'constraints' => [
                        new Email(
                            [
                                'message' => 'Vous devez entrer un email valide.'
                            ]
                        ),
                    ],
                ]
            )
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Confirm password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères',
                        'max' => 100,
                    ]),
                ],
            ])
        ;
    }
}
