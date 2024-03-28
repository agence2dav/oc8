<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\UserRepository;
use App\Entity\Task;

class TaskFormType extends AbstractType
{

    public function __construct(
        //private AbstractController $abstractController
        private readonly UserRepository $userRepository,
    ) {

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control mb-3'
                    ],
                    'label' => 'Titre',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrez un titre',
                        ]),
                        new Length([
                            'min' => 4,
                            'minMessage' => 'mini {{ limit }} caractères',
                            'max' => 255,
                            'maxMessage' => 'max {{ limit }} caractères',
                        ]),
                    ]
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'form-control mb-3',
                        'rows' => '12'
                    ],
                    'label' => 'Description',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le contenu ne peut être vide',
                        ]),
                        new Length([
                            'min' => 10,
                            'minMessage' => 'mini {{ limit }} caractères',
                        ]),
                    ],
                ]
            )
            ->getForm();
    }

}
