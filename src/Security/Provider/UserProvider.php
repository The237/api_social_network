<?php

namespace App\Security\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Undocumented class
 */
class UserProvider implements UserProviderInterface
{
    private UserLoaderInterface $userLoader;

    public function __construct(UserLoaderInterface $userLoader)
    {   
        $this->userLoader = $userLoader;
    }
    
    public function refreshUser(UserInterface $user)
    {

    }

    public function loadUserByUsername(string $username)
    {
        return $this->userLoader->loadUserByUsername($username);
    }

    public function supportsClass(string $class)
    {
        return $class === User::class;
    }
}