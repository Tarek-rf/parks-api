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

    public function getUser(string $email) : mixed {
        $pdo_values = ['email' => $email];
        $query = "SELECT * FROM ws_users WHERE 1";
        $condition = " AND email = :email";
        $query = $query . $condition;
        return $this->fetchSingle($query, $pdo_values);
    }
}
