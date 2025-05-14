<?php
namespace App\Common\Security;

use App\Common\UserSession;
use App\UseCases\Services\UserService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private UserService $userService;
    private RequestStack $requestStack;

    public function __construct(UserService $userService, RequestStack $requestStack)
    {
        $this->userService = $userService;
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_admin', [$this, 'isAdmin']),
        ];
    }

    public function isAdmin(): bool
    {
        $session = $this->requestStack->getSession();
        if (!$session) {
            return false;
        }

        $username = UserSession::getUsername($session);
        if (!$username) {
            return false;
        }

        return $this->userService->isUserAdmin($username);
    }
}
