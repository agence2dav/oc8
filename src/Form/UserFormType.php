<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserFormType extends AbstractType
{

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

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
                '_username',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ],
                    'label' => 'Nom d\'utilisateur',
                    'constraints' => [
                        new Length([
                            'min' => 2,
                            'minMessage' => 'Le nom d\'utilisateur doit faire au moins {{ limit }} caractères',
                            'max' => 100,
                        ]),
                    ],
                ]
            )
            ->add(
                '_password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
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
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'required' => true,
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
                    'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ],
                    'label' => 'Adresse email',
                    'constraints' => [
                        new Email(
                            [
                                'message' => 'Vous devez entrer un email valide.'
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'attr'  =>  [
                        'class' => 'form-control',
                        'style' => 'margin:5px 0;'
                    ],
                    'choices' => [
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'required' => true,
                    'label' => 'Rôles'
                ]
            );
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
        $builder->getForm();
    }
}
