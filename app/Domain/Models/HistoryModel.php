<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

/**
 * model use to fetch history data from the database 
 */
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

    /**
     * fetches a list of history based on the filters from the database
     * @param array $filters the filters
     * @return mixed the list of history
     */
    function getHistory(array $filters): mixed
    {
        $pdo_values = [];

        $query = "SELECT * FROM history WHERE 1  ";

        if(isset($filters["country"]) && !empty($filters["country"])){
            $query.= " AND country LIKE CONCAT('%',:name_of_country,'%')";
            $pdo_values["name_of_country"] = $filters["country"];
        }

        if(isset($filters["founded_date_after"]) && !empty($filters["founded_date_after"])){
            $query.= " AND founded_date > :founded_after";
            $pdo_values["founded_after"] = $filters["founded_date_after"];
        }

        if(isset($filters["founded_date_before"]) && !empty($filters["founded_date_before"])){
            $query.= " AND founded_date < :founded_before";
            $pdo_values["founded_before"] = $filters["founded_date_before"];
        }

        $history = $this->paginate(
            $query,
            $pdo_values
        );

        return $history;
    }

}
