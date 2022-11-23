<?php

namespace Framework\Security;


class Password
{
    private static array $errors = [];

    public static function __validSize(string $password, int $minSize, int $maxSize, bool $inclusive = true): bool
    {
        if (($inclusive) ?
            (strlen($password) >= $minSize) && (strlen($password) <= $maxSize)
            : (strlen($password) > $minSize) && (strlen($password) < $maxSize))
            return true;


        self::$errors[] = "Your password must be between $minSize and $maxSize";
        return false;
    }

    public static function __isStrong(string $password): bool
    {

        if ((preg_match("/[A-Z]/", $password)) &&
            (preg_match("/[a-z]/", $password)) &&
            (preg_match("/[\d]/", $password)) &&
            (preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $password)))
            return true;

        if (!preg_match("/[A-Z]/", $password))
            self::$errors[] = "Your password must contains uppercase letters";

        if (!preg_match("/[a-z]/", $password))
            self::$errors[] = "Your password must contains lowercase letters";

        if (!preg_match("/[\d]/", $password))
            self::$errors[] = "Your password must contains numbers";

        if (!(preg_match("/[\/'^£$%&*()}{@#~?<>,|=_+]/", $password)))
            self::$errors[] = "Your password must contains special characters : /'^£$%&*()}{@#~?<>,|=_+";

        return false;

    }

    public static function isValid(string $password, int $minSize = 5, int $maxSize = 40, bool $inclusiveSize = true): bool
    {
        self::$errors = [];
        return self::__validSize($password, $minSize, $maxSize, $inclusiveSize) && self::__isStrong($password);
    }

    public static function getLastErrors(): array
    {
        return self::$errors;
    }


    public static function hashPassword(string $password, string|int|null $algo = PASSWORD_BCRYPT, int $cost = PASSWORD_BCRYPT_DEFAULT_COST): string
    {
        return password_hash($password, $algo, ["cost" => $cost]);
    }
}