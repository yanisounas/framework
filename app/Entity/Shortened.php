<?php

namespace App\Entity;

use MicroFramework\Core\AbstractClass\Entity;
use MicroFramework\Core\ORM\Attributes\Column;

class Shortened extends Entity
{

    #[Column]
    public int $user_id;

    #[Column]
    public string $long_url;

    #[Column]
    public string $short_url;

    public function __construct()
    {
        parent::__construct();
    }
}