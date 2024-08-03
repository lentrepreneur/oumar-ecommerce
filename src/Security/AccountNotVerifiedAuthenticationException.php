<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AccountNotVerifiedAuthenticationException extends AuthenticationException
{
    public function getMessageKey(): string
    {
        return 'Votre compte n\'a pas été vérifié. Veuillez vérifier votre e-mail pour confirmer votre compte.';
    }

    public function getMessageData(): array
    {
        return [];
    }
}