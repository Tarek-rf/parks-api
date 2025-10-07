<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class HistoryModel extends BaseModel
{
    /**
     * creates an object of history model
     * @param $pdo the pdo service
     */
    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    
}
