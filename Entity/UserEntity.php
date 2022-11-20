<?php

namespace Framework\Entity;

use Framework\AbstractClass\Entity;
use Framework\ORM\Attributes\Column;

class UserEntity extends Entity
{
    public static ?string $TABLE_NAME = 'users';

    #[Column("VARCHAR", 50)]
    protected string $username;

    #[Column("VARCHAR", 50)]
    protected string $email;

    #[Column("VARCHAR", 50)]
    protected string $password;
}