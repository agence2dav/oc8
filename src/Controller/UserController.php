<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Service\UserService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    #[Route('/users', name: 'user_list')]
    public function index(): Response
    {
        $users = $this->userService->getAllModels();
        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    #[Route('/users/create', name: 'user_create')]
    public function createAction(Request $request): Response
    {
        $user = new User();
        $formUser = $this->createForm(UserFormType::class, $user);
        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $this->userService->saveUser(
                $user,
                $formUser->get('_password')->getData(),
                $formUser->get('roles')->getData()
            );
            $this->addFlash('success', 'L\'utilisateur a bien été ajouté.');
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/create.html.twig', [
            'form' => $formUser->createView()
        ]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function editAction(User $user = null, Request $request): Response
    {
        $formUser = $this->createForm(UserFormType::class, $user);
        $formUser->handleRequest($request);
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $this->userService->saveUser(
                $user,
                $formUser->get('_password')->getData(),
                $formUser->get('roles')->getData()
            );
            $this->addFlash('success', 'L\'utilisateur a bien été modifié.');
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $formUser->createView(),
            'user' => $user
        ]);
    }
}
