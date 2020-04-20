<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UserController extends AbstractController
{
    private $userService;
    private $logger;

    public function __construct(UserService $userService, LoggerInterface $logger)
    {
        $this->userService = $userService;
        $this->logger = $logger;
    }

    /**
     * @Route("/users", methods={"GET"}, name="api_user_list")
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @IsGranted("ROLE_USER")
     */
    public function listUser(Request $request): JsonResponse
    {
        try {
            $responseData = [
                'users' => $this->userService->getUsersArrayRest($request)
            ];

            if (!empty($responseData)) {
                return new JsonResponse(
                    $responseData,
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    [],
                    Response::HTTP_NO_CONTENT
                );
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());

            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Route("/user", methods={"GET"}, name="api_user_get")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function singleUser(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->getUserArrayRest($request);
            return new JsonResponse(
                $user,
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());

            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Route("/user/create", methods={"POST"}, name="api_user_create")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $this->userService->createUser($request);

            return new JsonResponse(
                ['status' => 'User has been created!'],
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());

            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Route("/user/{user}", methods={"PUT"}, name="api_user_update")
     * @param User $user
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateUser(Request $request, User $user): JsonResponse
    {
        try {
            $this->userService->updateUser($request, $user);

            return new JsonResponse(
                ['status' => 'User has been updated!'],
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());

            throw new Exception($e->getMessage());
        }
    }

    /**
     * @Route("/user/{user}", methods={"DELETE"}, name="api_user_delete")
     * @param User $user
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteUser(User $user): JsonResponse
    {
        try {
            $this->userService->deleteUser($user);

            return new JsonResponse(
                ['status' => 'User has been deleted!'],
                Response::HTTP_NO_CONTENT
            );
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());

            throw new Exception($e->getMessage());
        }
    }
}
