<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ValidatorInterface $validator;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
//        $this->registry = $registry;
        $this->validator = $validator;
        $this->userPasswordHasher = $userPasswordHasher;

        parent::__construct($registry, User::class);
    }

    /**
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function compareTokensWhenChangingPassword(?string $tokenReset): string
    {
        $currentUser = $this->findOneBy(['tokenReset' => $tokenReset]);
        $currentToken = $currentUser->getTokenReset();

        if ($currentToken !== $tokenReset) {
            $message = 'Tokens are not the same';
            $this->logger->warning($message);

            throw new \Exception($message);
        }

        return $currentToken;
    }
}
