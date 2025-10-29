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
}
