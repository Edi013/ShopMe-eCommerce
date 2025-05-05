<?php
namespace App\Common;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserSession
{
    final public const KEY_USER_ID   = 'user_id';
    final public const KEY_USERNAME  = 'username';

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

    public static function seedIdAndUsernameDevelopmentOnly(
        SessionInterface $session
    ): void {
        if (!$session->has(self::KEY_USER_ID)) {
            self::setUserId($session, '9ba0086b-4f61-4faf-a81d-233b34f9429b');
        }

        if (!$session->has(self::KEY_USERNAME)) {
            self::setUsername($session, 'admin');
        }
    }
}
