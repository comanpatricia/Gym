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

    private int $defaultPerPage;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        string $defaultPerPage
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->defaultPerPage = (int) $defaultPerPage;
    }

    /**
     * @Route("admin/user", name="admin_user", methods={"GET"})
     */
    public function getAllUsers(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $currentPage = (int) $request->query->get('page', 1);
        $perPage = $request->query->get('perPage', $this->defaultPerPage);

        $numberOfUsers = $this->userRepository->countAllUsers();
        $noPages = (int)( $numberOfUsers / $perPage);
        if ($numberOfUsers % $perPage !== 0) {
            $noPages++;
        }
        $result = $this->userRepository->showUsersPerPage($currentPage, $perPage);

        return $this->render('Admin/allUsers.html.twig', [
            'users' => $result,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'noPages' => $noPages,
        ]);
    }

    /**
     * @Route("admin/user/{id}", name="user_update", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function updateUser(Request $request, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->userRepository->findOneBy(['id' => $id]);
        if (null === $user) {
            $this->addFlash(
                'error',
                'User does not exist!'
            );
        }

        $form = $this->createForm(UpdateUserType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $firstName = $form->get('firstName')->getData();
            $lastName = $form->get('lastName')->getData();
            $email = $form->get('email')->getData();
            $phoneNumber = $form->get('phoneNumber')->getData();

            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->email = $email;
            $user->setPhoneNumber($phoneNumber);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'User updated successfully!'
            );

                return $this->redirectToRoute('admin_user', ['user' => $user]);
        }

        return $this->render('Admin/updateUser.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("admin/user/delete/{id}", name="user_delete")
     */
    public function softDeleteAnUser(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userToDelete = $this->userRepository->findOneBy(['id' => $id]);
        if (null === $userToDelete) {
            $this->addFlash(
                'error',
                'User does not exist!'
            );
        }

        $this->entityManager->remove($userToDelete);
        $this->entityManager->flush();

        $this->addFlash(
            'success',
            'User deleted successfully!'
        );

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("admin/user/create", name="user_create", methods={"GET", "POST"})
     */
    public function createUser(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();

        $form = $this->createForm(CreateNewUserType::class, $user)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
            $user->setRoles(array_values($user->getRoles()));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'User created successfully!'
            );

            return $this->redirectToRoute('admin_user');
        }

        return $this->render('Admin/createNewUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
