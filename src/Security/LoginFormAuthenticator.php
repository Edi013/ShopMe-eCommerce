<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Common\UserSession;

class LoginFormAuthenticator //extends AbstractAuthenticator
{

    public static function isUserAuthenticated(Request $request): bool
    {
        return UserSession::getUserId($request->getSession()) !== null
            && UserSession::getUserId($request->getSession()) !== '';
    }

    public static function handleAuthenticationState(Request $request): RedirectResponse
    {
        if (!self::isUserAuthenticated($request)) {
            return new RedirectResponse('/login');
        }
        return new RedirectResponse('/home');
    }

//    public function authenticate(Request $request): Passport
//    {
//        $userId = UserSession::getUserId($request->getSession());
//
//        if (empty($userId)) {
//            throw new AuthenticationException('User not authenticated.');
//        }
//
//        $userBadge = new UserBadge($userId);
//
//        return new SelfValidatingPassport($userBadge);
//    }
//
//    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
//    {
//        echo('zzzzzzzzzzzzzzz11zzz test zzzzzz11zzzzzzzzzz');
//
//        return new RedirectResponse('/login');
//    }
//
//    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
//    {
//        echo('zzzzzzzzzzzz22zzzzzz test zzzzzz22zzzzzzzzzz');
//        return new RedirectResponse('home');
//    }
//
//    public function supports(Request $request): bool
//    {
//        return true;
//    }
}
