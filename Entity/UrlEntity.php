<?php

namespace Framework\Entity;

use Framework\AbstractClass\Entity;
use Framework\ORM\Attributes\Column;

class UrlEntity extends Entity
{
    public static ?string $TABLE_NAME = "url";

    #[Column("INT")]
    protected int $user_id;

    #[Column("VARCHAR", 255)]
    protected string $url;

    #[Column("VARCHAR", 7)]
    protected string $token;

    #[Column("TIMESTAMP")]
    protected string $created_at;
}