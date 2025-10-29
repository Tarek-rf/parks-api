<?php

namespace App\Domain\Services;

use App\Domain\Models\VegetationsModel;
use App\Helpers\Core\Result;

class VegetationsService
{
    public function __construct(private VegetationsModel $vegetations_model) {}

    //* Methods to perform the create/update/delete operations INCLUDING the input validation step.
    public function doCreateVegetation(array $new_vegetation) : Result{

        $errors = [];
        //TODO VALIDATE the fields of the new item to be added tp the collection.

        // 1) If the fields are valid ->insert them into the DB.
        $last_inserted_id= $this->vegetations_model->createVegetation($new_vegetation[0]);

        //* Returning a successful operation
        $result= Result::success("The vegetation was created successfully!",["last_inserted_id"=>$last_inserted_id]);

        $errors [] = "oi, the vegetation  name is missing or cannot contain numbers";
        //*Returning a result object encapsulating a failed operation
        $result= Result::failure("The vegetation was not created!", $errors);

        //! Return a Result object
        return $result;
    }

    // public function doCreateVegetation(array $new_vegetation): Result {

    // }
}
