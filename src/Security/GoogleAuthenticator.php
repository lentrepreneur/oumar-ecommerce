<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use RuntimeException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class GoogleAuthenticator extends AbstractOAuthAuthenticator
{
    protected string $serviceName = 'google';

    protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $repository): ?User
    {
        if (!($resourceOwner instanceof GoogleUser)) {
            throw new RuntimeException('Expecting google user');
        }

        if (true != ($resourceOwner->toArray()['email_verified'] ?? null)) {
            throw new AuthenticationException('Email non verifie!');
        }

        return $repository->findOneBy([
            'googleId' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()
        ]);
    }
}
