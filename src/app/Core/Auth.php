<?php
namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function user(): ?array
    {
        if (!self::check()) return null;
        return [
            'id'       => Session::get('user_id'),
            'username' => Session::get('username'),
        ];
    }

    public static function require(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function guest(): void
    {
        if (self::check()) {
            header('Location: /');
            exit;
        }
    }
}