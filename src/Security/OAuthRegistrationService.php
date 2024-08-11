<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class OAuthRegistrationService
{
    public function __construct(
        private UserRepository $repository,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {

    }

    /**
     * Summary of persist
     * @param GoogleUser $resourceOwner
     */
    public function persist(ResourceOwnerInterface $resourceOwner)
    {
        $user = new User();

        $user->setEmail($resourceOwner->getEmail());
        $user->setGoogleId($resourceOwner->getId());

        $user->setFirstName($resourceOwner->getFirstName());
        $user->setLastName($resourceOwner->getLastName());
        $user->setVerified(true);
        $user->setRoles(['ROLE_USER']);

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $this->generateRandomPassword(8)
            )
        );

        $this->repository->add($user, true);

        return $user;
    }

    function generateRandomPassword($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }
}