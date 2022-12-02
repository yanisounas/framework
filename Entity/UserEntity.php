<?php

namespace Framework\Entity;

use Framework\AbstractClass\Entity;
use Framework\ORM\Attributes\Column;

class UserEntity extends Entity
{
    public static ?string $TABLE_NAME = "users";

    #[Column("VARCHAR", 255)]
    protected string $username;

    #[Column("VARCHAR", 255)]
    protected string $password;

    #[Column("VARCHAR", 255)]
    protected string $api_key;

    #[Column("int", default:0)]
    protected int $is_admin;
}