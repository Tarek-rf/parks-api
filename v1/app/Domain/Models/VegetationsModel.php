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
     * Creates a new vegetation by calling the insert function in base model
     * @param array $new_vegetation the values of the new vegetation to be inserted
     * @return int the id of the inserted vegetation
     */
    public function createVegetation(array $new_vegetation): int {

        return $this-> insert('vegetations',$new_vegetation);
    }

    /**
     * Updates a existing vegetation by passing new values and id to the update function in base model
     * @param array $to_update the values of the vegetation to be updated
     * @param array $vegetation_id the id of the vegetation to be updated
     * @return int the number of rows updated
     */
     public function updateVegetation(array $to_update,array $vegetation_id): int {
        return $this-> update('vegetations',$to_update,$vegetation_id);
    }

    /**
     * deletes an existing vegetation by passing the id and calling the delete function in base model
     * @param array $vegetation_id the id of the exiting vegetation to be deleted
     * @return int the number of rows deleted
     */
     public function deleteVegetation(array $vegetation_id): int {
        return $this-> delete('vegetations',$vegetation_id);
    }
}
