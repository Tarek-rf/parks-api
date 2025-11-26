<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use Psr\Http\Message\ServerRequestInterface;

class UserModel extends BaseModel
{
    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    public function createUser(array $new_user): int
    {

        return $this->insert("ws_users", $new_user);
    }
}
