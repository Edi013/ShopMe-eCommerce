<?php
namespace App\Common;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserSession
{
    final public const KEY_USER_ID   = 'user_id';
    final public const KEY_USERNAME  = 'username';
    final public const KEY_LAST_LOGIN  = 'last_login';


    public static function setUserId(SessionInterface $session, string $userId): void
    {
        $session->set(self::KEY_USER_ID, $userId);
    }

    public static function getUserId(SessionInterface $session): ?string
    {
        return $session->get(self::KEY_USER_ID);
    }

    public static function setUsername(SessionInterface $session, string $username): void
    {
        $session->set(self::KEY_USERNAME, $username);
    }

    public static function getUsername(SessionInterface $session): ?string
    {
        return $session->get(self::KEY_USERNAME);
    }

    public static function setLastLoginDate(SessionInterface $session): void
    {
        $dateTime = new \DateTime();
        $session->set(self::KEY_LAST_LOGIN, $dateTime->format(\DateTime::ATOM));
    }

    public static function getLastLoginDate(SessionInterface $session): ?\DateTimeImmutable
    {
        $stored = $session->get(self::KEY_LAST_LOGIN);
        return $stored ? new \DateTimeImmutable($stored) : null;
    }

    public static function isLastLoginOlderThan(SessionInterface $session, int $seconds): bool
    {
        $lastLogin = self::getLastLoginDate($session);
        if (!$lastLogin || $lastLogin->getTimestamp() === 0) {
            echo('last login is null');
            return true;
        }

        $now = new \DateTimeImmutable();
        $interval = $now->getTimestamp() - $lastLogin->getTimestamp();
        return $interval > $seconds;
    }

    public static function logout(SessionInterface $session): void
    {
        $session->clear();
        $session->getFlashBag()->clear();
        $session->invalidate();
    }
}
