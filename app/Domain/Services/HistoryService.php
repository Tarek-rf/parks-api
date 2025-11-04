<?php

namespace App\Domain\Services;

use App\Domain\Models\HistoryModel;
use App\Helpers\Core\Result;
use App\Validation\Validator;
use Slim\Exception\HttpNotFoundException;

class HistoryService
{
    public function __construct(private HistoryModel $history_model)
    {}

    //* Methods to preform the create|update|delete operations
    //* including the validation steps

    /**
     * validates values used to create a new history and calls the model
     * @param array $new_history the values of the new history
     * @return Result the result of the creation based on validation
     */
    public function doCreateHistory(array $new_history): Result
    {
        //TODO: Validate the fields of the new item to be added to the collection
        $validator = new Validator($new_history[0]);

        $rules = [
            'location_id' => [
                'required',
                'integer',
                // ['min', 1],
                // ['max', 999999],
            ],
            'founder' => [
                array('lengthMax', 120)
            ],
            'founded_date' => [
                ['dateFormat', 'Y-m-d'],
                ['dateAfter' , '1000-1-1']
            ],
            'most_interesting_fact' => [
                array('lengthMax', 200)
            ],
            'major_historic_accident' => [
                array('lengthMax', 200)
            ],
            'age_of_park' => [
                "integer",
                // ['min', 0],
                // ['max', 1100],
            ],
            'was_native_land' => [
                "integer",
                // ['min', 0],
                // ['max', 1],
            ],
            'preservation_laws_enacted' => [
                array('lengthMax', 200)
            ],
            'significant_restoration_year' => [
                "integer",
                // ['min', 1000],
                // ['max', 2100],
            ],
        ];

        $validator->mapFieldsRules($rules);


        if($validator->validate()) {
           //* 1) if the fields are valid -> insert them into DB
            $last_inserted_id = $this->history_model->createHistory($new_history[0]);

            //* returning a successful operation:
            $result = Result::success("The new History has been successfully created!",
            ["status" => "Success",
            "message" => "Successfully created a new history",
            "Most recent Inserted ID" => $last_inserted_id]);
        } else {
            //* returning a failed operation:
            $errors[] = $validator->errors();
            $result = Result::failure("The new History has had an error!",$errors);
        }

        //! return a result object
        return $result;
    }

    /**
     * validates the fields for updating an exiting history
     * @param mixed $request the request
     * @param array $updated_history the new values to update the history with
     * @param array $updated_history_id the id of the history to update
     * @throws \Slim\Exception\HttpNotFoundException if the resource is not found
     * @return Result the result of the updating based on validation
     */
    public function doUpdateHistory($request, array $updated_history, array $updated_history_id ): Result
    {
        //TODO: Validate the fields of the new item to be added to the collection
        $validator = new Validator($updated_history[0]);

        $rules = [
            'location_id' => [
                'integer',
                // ['min', 1],
                // ['max', 999999],
            ],
            'founder' => [
                array('lengthMax', 120)
            ],
            'founded_date' => [
                ['dateFormat', 'Y-m-d'],
                ['dateAfter' , '1000-1-1']
            ],
            'most_interesting_fact' => [
                array('lengthMax', 200)
            ],
            'major_historic_accident' => [
                array('lengthMax', 200)
            ],
            'age_of_park' => [
                "integer",
                // ['min', 0],
                // ['max', 1100],
            ],
            'was_native_land' => [
                "integer",
                // ['min', 0],
                // ['max', 1],
            ],
            'preservation_laws_enacted' => [
                array('lengthMax', 200)
            ],
            'significant_restoration_year' => [
                "integer",
                // ['min', 1000],
                // ['max', 2100],
            ],
        ];

        $validator->mapFieldsRules($rules);

        $id_validator = new Validator($updated_history_id);

        $id_rules = [
            'id' => [
                'integer',
                // ['min', 1],
                // ['max', 999999],
            ],
        ];

        $id_validator->mapFieldsRules($id_rules);


        if($validator->validate() && $id_validator->validate()) {
           //* 1) if the fields are valid -> insert them into DB
            $rowAffected = $this->history_model->updateHistory($updated_history[0],$updated_history_id);

            if($rowAffected == 0) {
                $id = $updated_history_id['id'];
                throw new HttpNotFoundException($request,"The history was not able to be updated with ID: $id since it dose not exist");
            }
            //* returning a successful operation:
            $result = Result::success("The History has been successfully updated!",
            ["status" => "Success",
            "message" => "Successfully updated a history"]);
        } else {
            //* returning a failed operation:
            $errors[] = $validator->errors();
            $result = Result::failure("The updated History has had an error!",$errors);
        }

        //! return a result object
        return $result;
    }

    /**
     * validates the id for deleting an exiting history
     * @param mixed $request the request
     * @param array $history_to_delete the id of the history to delete
     * @throws \Slim\Exception\HttpNotFoundException if the resource is not found
     * @return Result the result of the deleting based on validation
     */
    public function doDeleteHistory($request, array $history_to_delete): Result
    {
        //TODO: Validate the fields of the new item to be added to the collection
        $validator = new Validator($history_to_delete);

        $rules = [
            'id' => [
                'required',
                'integer',
                // ['min', 1],
                // ['max', 999999],
            ],
        ];

        $validator->mapFieldsRules($rules);


        if($validator->validate()) {
           //* 1) if the fields are valid -> insert them into DB
            $rowAffected = $this->history_model->deleteHistory($history_to_delete);

            if($rowAffected == 0) {
                $id = $history_to_delete['id'];
                throw new HttpNotFoundException($request,"The history was not able to be deleted with ID: $id since it dose not exist");
            }
            //* returning a successful operation:
            $result = Result::success("The new History has been successfully Deleted!",
            ["status" => "Success",
            "message" => "Successfully deleted a history"
            ]);
        } else {
            //* returning a failed operation:
            $errors[] = $validator->errors();
            $result = Result::failure("The History has had an error!",$errors);
        }

        //! return a result object
        return $result;
    }
}
