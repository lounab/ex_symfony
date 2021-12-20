<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->userRepository = $entityManager->getRepository(User::class);

    }


    #[Route('/users/create', name: 'create_user')]
    public function createUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->userRepository->save($user);

            return $this->redirectToRoute('users_list');
        }

        return $this->renderForm('user/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/users/{id}/update', name: 'update_user')]
    public function updateUser(int $id, Request $request): Response
    {
        $user = $this->userRepository->find($id);

        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->userRepository->save($user);

            return $this->redirectToRoute('users_list');
        }

        return $this->renderForm('user/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/users', name: 'users_list')]
    public function userslist(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/users/{id}', name: 'users_details')]
    public function userDetails(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (null === $user) {
            throw new NotFoundHttpException();
        }

        return $this->render('user/details.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/users/{id}/delete', name: 'delete_user')]
    public function deleteUser(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if (null === $user){
            throw new NotFoundHttpException();
        }

        $this->userRepository->delete($user);

        return $this->redirectToRoute('users_list');
    }

}
