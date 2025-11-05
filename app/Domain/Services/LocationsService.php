<?php

namespace App\Domain\Services;

use App\Domain\Models\LocationsModel;
use App\Helpers\Core\Result;

class LocationsService
{
    public function __construct(private LocationsModel $locations_model) {}

    //* Methods to perform the create/update/delete operations INCLUDING the input validation step.
    public function doCreateLocation(array $new_locations): Result
    {
        //TODO: Validate the fields of the new item to be added to the collection.

        //* If the fields are valid -> Insert them into the DB
        $last_inserted_id = $this->locations_model->createLocation($new_locations[0]);

        //* Returning a successful operation
        $result = Result::success("The location was created successfully", ["last inserted id" => $last_inserted_id]);

        //* Returning a failed operation
        $errors[] = "There was an error in bla bla bla";
        $failed_result = Result::failure("The location could not be created", $errors);

        return $result;
    }

    public function doDeleteLocation(array $where_condition): Result
    {
        //TODO: Validate the id of the record to be deleted from the collection.

        //* If the fields are valid -> Delete the record from DB
        $affected_rows = $this->locations_model->deleteLocation($where_condition);

        //* Returning a successful operation
        $result = Result::success("The location was deleted successfully", ["affected rows" => $affected_rows]);

        //* Returning a failed operation
        $errors[] = "There was an error during the deletion operation";
        $failed_result = Result::failure("The location could not be deleted", $errors);

        return $result;
    }

    public function doUpdateLocation(array $data, array $where_condition): Result
    {
        //TODO: Validate the fields of the item and the where conditions to be updated in the collection.

        //* If the fields are valid -> update the record in the DB
        $affected_rows = $this->locations_model->updateLocation($data[0], $where_condition);

        //* Returning a successful operation
        $result = Result::success("The location was updated successfully", ["affected rows" => $affected_rows]);

        //* Returning a failed operation
        $errors[] = "There was an error during the deletion operation";
        $failed_result = Result::failure("The location could not be deleted", $errors);

        return $result;
    }
}
