<?php

namespace App\Domain\Services;

use App\Domain\Models\HistoryModel;
use App\Helpers\Core\Result;
use App\Validation\Validator;

class HistoryService
{
    public function __construct(private HistoryModel $history_model)
    {}

    //* Methods to preform the create|update|delete operations
    //* including the validation steps
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
}
