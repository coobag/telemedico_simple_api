<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService implements UserServiceInterface
{

    private $logger;
    private $em;
    private $container;
    private $passwordEncoder;

    /**
     * UserService constructor.
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, ContainerInterface $container, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->container = $container;

    }

    public function createUser(Request $request): bool
    {
        $data = json_decode($request->getContent(), true);

        $userRepository = $this->em->getRepository(User::class);

        return $userRepository->createUser($data);
    }

    public function getUsersArrayRest(Request $request): array
    {
        $data = $request->query->all();

        /**
         * data from Request
         * pagination, filters, ...
         */
        $page = $data['page'];
        $pageElements = $this->container->getParameter('page_elements');
        $offset = ($page - 1) * $pageElements;

        /**
         * should be dedicated function on UserRepository for filters and pagination
         */
        $users = $this->em->getRepository(User::class)->findBy([], ['id' => 'ASC'], $pageElements, $offset);

        /**
         * ArrayRest for JsonResponse
         */
        $userArray = array();
        foreach($users as $user) {
            $userArray[] = $user->toArrayRest();
        }

        return $userArray;
    }

    public function getUserArrayRest(User $user): array
    {
        return $user->toArrayRest();
    }

    public function updateUser(Request $request, User $user): User
    {
        $data = json_decode($request->getContent(), true);

        $userRepository = $this->em->getRepository(User::class);

        return $userRepository->updateUser($user, $data);
    }

    public function deleteUser(User $user): void
    {
        $userRepository = $this->em->getRepository(User::class);
        $userRepository->deleteUser($user);
    }

}