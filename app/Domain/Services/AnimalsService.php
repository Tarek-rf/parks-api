<?php

namespace App\Domain\Services;

use App\Domain\Models\AnimalsModel;
use App\Helpers\Core\Result;

class AnimalsService
{
    public function __construct(private AnimalsModel $animals_model) {}

    //METHODs to perform the crud operations INCLUDING the input validation step.

    public function doCreateAnimal(array $new_animals): Result
    {

        $errors = [];

        //TODO Validate the fields of the new item to be added to the collection.

        //1) If the fields are valid -> insert them to the db.
        $last_inserted_id = $this->animals_model->createAnimal($new_animals[0]);

        // returning a successful operation
        $result = Result::success("The animal was created successfully!", ["last_inserted_id" => $last_inserted_id]);

        //returning a failed operation
        $errors[] = "The animal name cant contain numbers";
        //$result = Result::failure("The animal was not created", $errors);
        return $result;
    }
}
