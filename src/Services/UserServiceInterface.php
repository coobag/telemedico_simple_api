<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface UserService
 */
interface UserServiceInterface
{
    public function getUsersArrayRest(Request $request);
    public function getUserArrayRest(User $user);
    public function createUser(Request $request);
    public function updateUser(Request $request, User $user);
    public function deleteUser(User $user);
}