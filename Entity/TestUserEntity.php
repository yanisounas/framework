<?php

namespace Framework\Entity;

use Framework\AbstractClass\Entity;
use Framework\ORM\Attributes\Column;

class TestUserEntity extends Entity
{
    public static ?string $TABLE_NAME = 'testusers';

    #[Column("VARCHAR", 100)]
    protected string $username;

    #[Column("VARCHAR", 100)]
    protected string $email;

    #[Column("VARCHAR", 65)]
    protected string $password;

    #[Column("VARCHAR", 100)]
    protected string $picture;

    #[Column("int", 11, 0)]
    protected int $user_kind;

}