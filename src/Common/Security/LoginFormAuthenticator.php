<?php
namespace App\Common\Security;

use App\Common\UserSession;
use Symfony\Component\HttpFoundation\Request;

class LoginFormAuthenticator
{
    public const MAX_LOGIN_AGE_IN_SECONDS = 3600;

    public static function isUserAuthenticated(Request $request): bool
    {
        $session = $request->getSession();

        $userId = UserSession::getUserId($session);
        $isLoggedIn = $userId !== null && $userId !== '';

        if (!$isLoggedIn) {
            return false;
        }

        return !UserSession::isLastLoginOlderThan($session, LoginFormAuthenticator::MAX_LOGIN_AGE_IN_SECONDS);
    }
}