<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;


class VegetationsModel extends BaseModel
{

    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    function getVegetations(array $filters): array
    {
        $pdo_values = [];
        $query = "SELECT * FROM vegetations WHERE 1";
        if (isset($filters["species_name"]) && !empty($filters["species_name"])) {
            $filter_value = $filters["species_name"];
            $query .= " AND species_name LIKE ";
        }
        return [];
    }
}
