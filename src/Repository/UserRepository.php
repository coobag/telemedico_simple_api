<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Exception;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $em;
    private $passwordEncoder;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($registry, User::class);
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser($data): bool
    {
        $user = new User();

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $password = $data['password'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            return false;
        }

        $passwordEncoded = $this->passwordEncoder->encodePassword($user, $password);

        $user
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setUsername($email)
            ->setPassword($passwordEncoded);

        $this->em->persist($user);
        $this->em->flush();

        return true;
    }

    public function updateUser(User $user, $data): User
    {
        !empty($data['firstName']) ? $user->setFirstname($data['firstName']) : false;
        !empty($data['lastName']) ? $user->setLastname($data['lastName']) : false;
        !empty($data['email']) ? $user->setEmail($data['email']) : false;
        !empty($data['password']) ? $user->setPassword($this->passwordEncoder->encodePassword($user, $data['password'])) : false;

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function deleteUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }
}
