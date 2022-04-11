<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Type\Admin\CreateNewUserType;
use App\Form\Type\Admin\UpdateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @Route("admin/users", name="admin_users", methods={"GET"})
     */
    public function getAllUsers(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('Admin/allUsers.html.twig', ['users' => $users ]);
    }

    /**
     * @Route("admin/user/{id}", name="user_update")
     */
    public function updateUser(Request $request, int $id): Response
    {
        $user = $this->userRepository->find(['id' => $id]);
        if (null === $user) {
            return new Response('User does not exist', Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UpdateUserType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firstName = $form->get('firstName')->getData();
            $lastName = $form->get('lastName')->getData();
            $email = $form->get('email')->getData();
            $phoneNumber = $form->get('phoneNumber')->getData();

            if ($user !== null) {
                $user->firstName = $firstName;
                $user->lastName = $lastName;
                $user->email = $email;
                $user->setPhoneNumber($phoneNumber);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $this->redirectToRoute('admin_users');
            }
        }

        return $this->render('Admin/updateUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/user/delete/{id}", name="user_delete")
     */
    public function softDeleteAnUser(int $id): Response
    {
        $userToDelete = $this->userRepository->findOneBy(['id' => $id]);
        if (null === $userToDelete) {
            return new Response('User does not exist', Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($userToDelete);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("admin/user/create", name="user_create")
     */
    public function createUser(Request $request): Response
    {
        $form = $this->createForm(CreateNewUserType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firstName = $form->get('firstName')->getData();
            $lastName = $form->get('lastName')->getData();
            $email = $form->get('email')->getData();
            $cnp = $form->get('cnp')->getData();
            $phoneNumber = $form->get('phoneNumber')->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            $user = new User();
            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->email = $email;
            $user->cnp = $cnp;
            $user->setPhoneNumber($phoneNumber);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
//            $user->setRoles($roles);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('Admin/createNewUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
