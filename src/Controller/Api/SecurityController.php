<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="api_register", methods={"POST"})
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function register(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = new User();
        $data = json_decode($request->getContent(), true);

        $errors = [];
        /**
         * validators
         */

        if(!$errors)
        {
            $encodedPassword = $passwordEncoder->encodePassword($user, $data['password']);
            $user->setFirstname($data['firstName']);
            $user->setLastname($data['lastName']);
            $user->setEmail($data['email']);
            $user->setUsername($data['email']);
            $user->setPassword($encodedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json(['result' => true]);
        }

        return $this->json([
            'errors' => $errors
        ], 400);
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login()
    {
        return $this->json(['result' => true]);
    }
}
