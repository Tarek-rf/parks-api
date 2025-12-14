<?php

namespace App\Domain\Models;

class LogModel extends BaseModel
{
    /**
     *
     * Creates a new Location in the DB
     *
     * @param array $log
     * @return int
     */
    public function createLog(array $log): int
    {

        // $sql = "INSERT INTO locations VALUES (name, country, province, address, latitude)"
        return $this->insert('ws_log', $log);
        // return $this->update('locations', $existing_location, ["location_id" => $location_id]);

    }
}
