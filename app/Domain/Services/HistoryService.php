<?php

namespace App\Domain\Services;

use App\Domain\Models\HistoryModel;
use App\Helpers\Core\Result;

class HistoryService
{
    public function __construct(private HistoryModel $history_model)
    {}

    //* Methods to preform the create|update|delete operations
    //* including the validation steps
    public function doCreateHistory(array $new_history): Result
    {
        $errors = [];
        //TODO: Validate the fields of the new item to be added to the collection
        $result = Result::failure("The new History has been successfully created!");

        //* 1) if the fields are valid -> insert them into DB
        $last_inserted_id = $this->history_model->createHistory($new_history[0]);

        //* returning a successful operation:
        $result = Result::success("The new History has been successfully created!",["last_inserted_id" => $last_inserted_id]);

        //* returning a failed operation:
        $errors[] = "The history founder is missing";
        // $result = Result::failure("The new History has had an error!",$errors);

        //! return a result object
        return $result;
    }
}
