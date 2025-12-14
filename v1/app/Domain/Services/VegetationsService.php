<?php

namespace App\Domain\Services;

use App\Domain\Models\VegetationsModel;
use App\Helpers\Core\Result;
use App\Validation\Validator;

class VegetationsService extends BaseService
{
    public function __construct(private VegetationsModel $vegetations_model) {}

    //* Methods to perform the create/update/delete operations INCLUDING the input validation step.
     /**
     * Validates values used to create a new history and calls the model
     * @param array $new_vegetation the values of the new vegetation
     * @return Result the result of the creation based on validation
     */
    public function doCreateVegetation(array $new_vegetation): Result
    {

        $rules = array(
        "species_name" => [
            'required',
            ['regex', '/^[a-zA-Z\s]+$/', 'message' => 'species_name accept only letters and space'],
        ],
        "type" => [
            'required',
            'alpha',
            ['in', ['tree', 'shrub', 'flower', 'grass'], 'message' => 'type should be tree, flower, shrub or grass']
        ],
        "scientific_name" => [
            'required',
            ['regex', '/^[a-zA-Z\s]+$/','message' => 'scientific_name accept only letters and space'],
        ],
        "climate_pref" => [
            'alpha',
        ],
        "conservation_status" => [
            'alpha',
        ],
        "avg_canopy_diameter_m" => [
            'numeric',
            ['min', 0],
            ['max', 20]
        ],
        "growth_rate" => [
            ['in', ['slow', 'moderate', 'fast'], 'message' => 'growth_rate should be slow, moderate or fast']
        ],
        "bloom_season" => [
            ['in', ['spring', 'summer', 'fall', 'winter'], 'message' => 'bloom_season should be spring, summer, fall, winter']
        ],
        "soil_preference" => [
            'alpha',
        ],
        "is_invasive" => [
            'required',
            ['regex','/^[01]$/','message' => 'is_invasive should be 0 or 1 as a string'] ]
    );
         $validator = $this->validateInput($new_vegetation[0], $rules);
        if ($validator ===  true) {
            // 1) If the fields are valid ->insert them into the DB.
            $last_inserted_id = $this->vegetations_model->createVegetation($new_vegetation[0]);

            //* Returning a successful operation
            $result = Result::success(
                "The vegetation was created successfully!",
                [
                    "status" => "Success",
                    "message" => "Successfully created a new vegetation",
                    "last_inserted_id" => $last_inserted_id
                ]
            );
        } else {
            //*Returning a result object encapsulating a failed operation
            $errors[] = $validator;
            $result = Result::failure("The vegetation was not created!!", $errors);
        }
        //! Return a Result object
        return $result;
    }
 /**
     * Validates the fields for updating an existing vegetation and updates it with the new values
     * @param array $updated_vegetation the new values to update the vegetation row of the designated id
     * @param array $vegetation_id the id of the vegetation to be updated
     * @return Result the result of the updating based on validation
     */
    public function doUpdateVegetation(array $vegetation_id, array $updated_vegetation): Result
    {
         $rules = array(
        "species_name" => [
            'required',
            ['regex', '/^[a-zA-Z\s]+$/', 'message' => 'species_name accept only letters and space'],
        ],
        "type" => [
            'required',
            'alpha',
            ['in', ['tree', 'shrub', 'flower', 'grass'], 'message' => 'type should be tree, flower,  or grass']
        ],
        "scientific_name" => [
            'required',
            ['regex', '/^[a-zA-Z\s]+$/','message' => 'scientific_name accept only letters and space'],
        ],
        "climate_pref" => [
            'alpha',
        ],
        "conservation_status" => [
            'alpha',
        ],
        "avg_canopy_diameter_m" => [
            'numeric',
            ['min', 0],
            ['max', 10]
        ],
        "growth_rate" => [
            ['in', ['slow', 'moderate', 'fast'], 'message' => 'growth_rate should be slow, moderate or fast']
        ],
        "bloom_season" => [
            ['in', ['spring', 'summer', 'fall', 'winter'], 'message' => 'bloom_season should be spring, summer, fall, winter']
        ],
        "soil_preference" => [
            'alpha',
        ],
        "is_invasive" => [
            'required',
            ['regex','/^[01]$/','message' => 'is_invasive should be 0 or 1'] ]
    );
        $validator =  $this->validateInput($updated_vegetation[0], $rules);

        $id_rules = [
            'id' => [
                'integer',
                ['min', 1],
                ['max', 999999],
            ],
        ];

        $validator_id =  $this->validateInput($vegetation_id, $id_rules);

        if ($validator=== true && $validator_id=== true) {
            // 1) If the fields are valid ->insert them into the DB.
            $updatedRows = $this->vegetations_model->updateVegetation($updated_vegetation[0], $vegetation_id);

            if ($updatedRows == 0) {
                $result = Result::failure("The vegetation was not updated!",[
                    "status" => "Failure",
                    "message" => "the id either does not exist or was already updated"
                ]);
            } else{
            //* returning a successful operation:
            $result = Result::success(
                "The vegetation was updated successfully!",
                [
                    "status" => "Success",
                    "message" => "Successfully updated a vegetation"
                ]
            );
        }
        } else {
            //*Returning a result object encapsulating a failed operation
            $errors[] = $validator;
            $result = Result::failure("The vegetation was not updated!", $errors);
        }

        //! Return a Result object
        return $result;
    }
    /**
         * Validates the id for deleting an exiting vegetation and deletes the desired vegetation
         * @param array $vegetation_id the id of the vegetation to be deleted
         * @return Result the result of the deleting based on validation
         */
    public function doDeleteVegetation( array $vegetation_id): Result
    {

        $validator = new Validator($vegetation_id);

         $rules = [
            'id' => [
                'required',
                 'integer',
                ['min', 1],
                ['max', 999999],
            ],
        ];

        $validator = $this->validateInput(["id" => $vegetation_id[0]], $rules);
        if ($validator === true) {
            // 1) If the fields are valid ->insert them into the DB.
            $deletedRows = $this->vegetations_model->deleteVegetation(["id" =>$vegetation_id[0]]);

            if ($deletedRows == 0) {
                $result = Result::failure("The vegetation was not deleted!", [
                    "status" => "Failure",
                    "message" => "the id either does not exist or was already deleted"
                ]);
            }else{
            //* Returning a successful operation
            $result = Result::success(
                "The vegetation was deleted successfully!",
                [
                    "status" => "Success",
                    "message" => "Successfully deleted a vegetation",
                    "number of deleted rows"=>$deletedRows
                ]
            );
        }
        } else {
            //*Returning a result object encapsulating a failed operation
            $errors[] = $validator;
            $result = Result::failure("The vegetation was not deleted!", $errors);
        }

        //! Return a Result object
        return $result;
    }
}
