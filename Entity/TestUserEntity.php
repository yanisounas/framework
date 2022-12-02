<?php

namespace Framework\Entity;

use Framework\AbstractClass\Entity;
use Framework\ORM\Attributes\Column;

class TestUserEntity extends Entity
{
    public static ?string $TABLE_NAME = 'testusers';

    #[Column("VARCHAR", length: 100)]
    protected string $username;

    #[Column("VARCHAR", length: 100)]
    protected string $email;

    #[Column("VARCHAR", length: 65)]
    protected string $password;

    #[Column("int", default: 0)]
    protected int $user_kind;

}