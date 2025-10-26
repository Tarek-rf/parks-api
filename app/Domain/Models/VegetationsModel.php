<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;


class VegetationsModel extends BaseModel
{
    /**
     * Creates an object of vegetations model
     * @param $pdo the pdo service that contains the established DB connection
     */
    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    /**
     * Fetches a list of vegetaions depending on the filters chosen
     * @param array $filters the filters
     * @return array the list of vegetations filtered
     */
    function getVegetations(array $filters): array
    {
        $pdo_values = [];
        $query = "SELECT * FROM vegetations WHERE 1 ";

        //filter by vegetation type like tree, flower, grass etc...
        if (isset($filters["type"]) && !empty($filters["type"])) {
            $query .= " AND type LIKE CONCAT('%',:vegetations_type,'%')";
            $pdo_values["vegetations_type"] = $filters["type"];
        }

        //filter vegetations by bloom season like sprin, winter etc...
        if (isset($filters["bloom"]) && !empty($filters["bloom"])) {
            $query .= " AND bloom_season LIKE CONCAT('%',:vegetations_bloom,'%')";
            $pdo_values["vegetations_bloom"] = $filters["bloom"];
        }

        //filter vegetations by growth rate like fast, moderate, slow
        if (isset($filters["growth"]) && !empty($filters["growth"])) {
            $query .= " AND growth_rate LIKE CONCAT('%',:vegetations_growth,'%')";
            $pdo_values["vegetations_growth"] = $filters["growth"];
        }

        //sort and order the query string
        if (isset($filters["sort_by"]) && !empty($filters["sort_by"]) && isset($filters["order"]) && !empty($filters["order"])) {
            $query .= " ORDER BY {$filters["sort_by"]} {$filters["order"]}";
        }

        $vegetations = $this->paginate(
            $query,
            $pdo_values
        );
        return $vegetations;
    }

    /**
     * fetches a single vegetation based on the Id prOvided
     * @param int $id Id of vegetation to filter by
     * @return mixed returns the vegetation that matches the Id or null/ nothing if there is no matching Id
     */
    public function getVegetationById(int $id): mixed
    {

        $sql = "SELECT * FROM vegetations WHERE id = :vegetations_id";
        $vegetations = $this->fetchSingle($sql, ["vegetations_id" => $id]);

        return $vegetations;
    }
}
