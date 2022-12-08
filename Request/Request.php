<?php

namespace Framework\Request;

class Request
{
    public static function METHOD()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getFirst(?string $key = null): string|array|null
    {
        if (!is_null(self::get($key)) && !empty(self::get($key)))
            return self::get($key);

        if (!is_null(self::post($key)) && !empty(self::post($key)))
            return self::post($key);

        if (!is_null(self::stream($key)) && !empty(self::stream($key)))
            return self::stream($key);

        return null;
    }

    public static function getAll(?string $key = null): string|array|null
    {
        if (!$key)
            return ["GET" => self::get(), "POST" => self::post(), "STREAM" => self::stream()];

        $temp = [];
        if (self::get($key))
            $temp["GET"] = self::get($key);

        if (self::post($key))
            $temp["POST"] = self::post($key);

        if (self::stream($key))
            $temp["STREAM"] = self::stream($key);

        return $temp;
    }

    public static function get(?string $key = null): string|array|null
    {
        if (!$key && !empty($_GET))
            return $_GET;

        if (isset($_GET[$key]))
            return $_GET[$key];

        return null;
    }

    public static function post(?string $key = null): string|array|null
    {
        if (!$key && !empty($_POST))
            return $_POST;

        if (isset($_POST[$key]))
            return $_POST[$key];

        return null;
    }

    public static function file(?string $key = null): string|array|null
    {
        if (!$key && !empty($_FILES) )
            return $_FILES;

        return null;
    }

    public static function stream(?string $key = null, bool $json = true): string|array|null
    {
        $request = file_get_contents("php://input");

        if (!$key)
        {
            $request = json_decode($request, true);
            if ($request)
                return $request[$key];
            else
                return null;
        }
        if ($json)
            return json_decode($request, true);
        else
            return $request;
    }

    public static function useSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
            session_start();
    }

    public static function removeSession(): void
    {
        self::useSession();
        session_unset();
        session_destroy();
    }

    public static function session(?string $key = null): string|array|null
    {
        self::useSession();
        if (!isset($_SESSION))
            return null;

        if (!$key && !empty($_SESSION))
            return $_SESSION;

        if (isset($_SESSION[$key]))
            return $_SESSION[$key];

        return null;
    }
}