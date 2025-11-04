<?php

namespace App\Domain\Models;

use App\Exceptions\HttpInvalidSortingParamsException;
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

        if(isset($filters["was_native_land"]) && !empty($filters["was_native_land"])){
            $query.= " AND was_native_land = :native_land";
            $pdo_values["native_land"] = $filters["was_native_land"] == "true" ? 1 : 0;
        }

        if(isset($filters["founded_date_after"]) && !empty($filters["founded_date_after"])){
            $query.= " AND founded_date > :founded_after";
            $pdo_values["founded_after"] = $filters["founded_date_after"];
        }

        if(isset($filters["founded_date_before"]) && !empty($filters["founded_date_before"])){
            $query.= " AND founded_date < :founded_before";
            $pdo_values["founded_before"] = $filters["founded_date_before"];
        }

        if(isset($filters["sort_by"]) && !empty($filters["sort_by"]) && isset($filters["order"]) && !empty($filters["order"])){
            $query.= " ORDER BY {$filters["sort_by"]} {$filters["order"]}";
        }

        $history = $this->paginate(
            $query,
            $pdo_values
        );

        return $history;
    }

    public function createHistory(array $new_history): int {
        return $this->insert("history",$new_history);
    }

    public function updateHistory(array $existing_history): int {
        // last param should be the where condition  so the id primary key
        return $this->update("history",$existing_history, ["history_id" => 1]);
    }

    


}
