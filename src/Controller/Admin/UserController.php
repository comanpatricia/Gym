<?php

namespace App\Controller\Admin;

use App\Form\Type\Admin\UpdateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("admin/users", name="admin_users", methods={"GET"})
     */
    public function getAllUsers(Request $request): Response
    {
        $allUsers = $this->userRepository->findAll();

        return $this->render('Admin/allUsers.html.twig', ['allUsers' => $allUsers ]);
    }

    /**
     * @Route("admin/user/{id}", name="user_update")
     */
    public function updateUser(Request $request): Response
    {
        $form = $this->createForm(UpdateUserType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $firstName = $form->get('firstName')->getData();
            $lastName = $form->get('lastName')->getData();
            $email = $form->get('email')->getData();
            $phoneNumber = $form->get('phoneNumber')->getData();

            $user = $this->userRepository->findOneBy(['email' => $email]);

            if ($user !== null) {
                $user->firstName = $firstName;
                $user->lastName = $lastName;
                $user->email = $email;
                $user->setPhoneNumber($phoneNumber);

                $this->entityManager->persist($user);
                $this->entityManager->flush();


                return $this->render('Admin/updateUser.html.twig');

//                return $this->render('Admin/allUsers.html.twig', [
//                    'form' => $form->createView(),
//                ]);
            }
        }
        return $this->render('resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
