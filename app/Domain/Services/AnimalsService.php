<?php

namespace App\Domain\Services;

use App\Domain\Models\AnimalsModel;
use App\Helpers\Core\Result;
use App\Validation\Validator;

class AnimalsService extends BaseService
{
    public function __construct(private AnimalsModel $animals_model) {}

    //METHODs to perform the crud operations INCLUDING the input validation step.

    public function doCreateAnimal(array $new_animals): Result
    {
        $rules = array(
            'common_name' => array(
                'required',
                array('lengthBetween', 2, 100),
            ),
            'scientific_name' => array(
                'required',
                array('lengthBetween', 2, 140)
            ),
            'class_name' => [
                array('lengthBetween', 2, 80)
            ],
            'family_name' => [
                array('lengthBetween', 2, 80)
            ],
            'conservation_status' => [
                'required',
                array('in', array("least_concern", "threatened", "extinct"))
            ],
            'average_weight' => array(
                'numeric',
                array('min', 0.01,),
                array('max', 999999.99)
            ),
            'average_height' => [
                'numeric',
                array('min', 0.01,),
                array('max', 999999.99)
            ],
            'diet' => [
                'required',
                array('in', array("carnivore", "herbivore", "omnivore"))
            ],
            'phylum' => [
                array('lengthBetween', 2, 80)
            ],

        );

        $valid = $this->validateInput($new_animals[0], $rules);

        if ($valid === true) {
            $last_inserted_id = $this->animals_model->createAnimal($new_animals[0]);

            // returning a successful operation
            $result = Result::success("The animal was created successfully!", ["last_inserted_id" => $last_inserted_id]);
        } else {
            $errors[] = $valid;
            $result = Result::failure("The animal was not created", $errors);
        }
        return $result;
    }

    public function doUpdateAnimal(array $update_animal, array $condition): Result
    {

        $rules = array(
            'common_name' => array(
                'required',
                array('lengthBetween', 2, 100),
            ),
            'scientific_name' => array(
                'required',
                array('lengthBetween', 2, 140)
            ),
            'class_name' => [
                array('lengthBetween', 2, 80)
            ],
            'family_name' => [
                array('lengthBetween', 2, 80)
            ],
            'conservation_status' => [
                'required',
                array('in', array("least_concern", "threatened", "extinct"))
            ],
            'average_weight' => array(
                'numeric',
                array('min', 0.01,),
                array('max', 999999.99)
            ),
            'average_height' => [
                'numeric',
                array('min', 0.01,),
                array('max', 999999.99)
            ],
            'diet' => [
                'required',
                array('in', array("carnivore", "herbivore", "omnivore"))
            ],
            'phylum' => [
                array('lengthBetween', 2, 80)
            ],

        );

        $valid = $this->validateInput($update_animal[0], $rules);

        $rules_id = [
            'id' => [
                'integer',
                ['min', 1],
                ['max', 99999],
            ]
        ];

        $valid_id = $this->validateInput($condition, $rules_id);

        if ($valid === true && $valid_id === true) {
            $last_updated_id = $this->animals_model->updateAnimal($update_animal[0], $condition);

            if ($last_updated_id == 0) {
                $id = $condition['id'];
                $result = Result::failure("The animal could not be updated with the given ID", [
                    "status" => "Failure",
                    "message" => "The updated animal has had an error so it was not able to be updated. "
                ]);
            } else {

                $result = Result::success("The animal was updated successfully!", [
                    "status" => "Success",
                    "message" => "Successfully updated a history"
                ]);
            }
        } else {
            $errors[] = $valid;
            $result = Result::failure("The updated animal has had an error!", $errors);
        }
        return $result;
    }

    public function doDeleteAnimal(array $condition): Result
    {

        $rules = [
            "id" => [
                'required',
                'integer',
                ['min', 1],
                ['max', 99999]
            ]
        ];

        $valid = $this->validateInput(['id' => $condition[0]], $rules);

        if ($valid === true) {
            $rowsAffected = $this->animals_model->deleteAnimal(["id" => $condition[0]]);

            if ($rowsAffected == 0) {
                $id = $condition[0];
                $result = Result::failure("The deleted animal has had an error!", [
                    "status" => "Failure",
                    "message" => "The deleted animal has had an error since ID: $id dose not exist "
                ]);
            } else {
                $result = Result::success(
                    "The animals was deleted successfully",
                    [
                        "status" => "Success",
                        "message" => "Successfully deleted animals"
                    ]
                );
            }
        } else {
            $errors[] = $valid;
            $result = Result::failure("Could not delete the animal with the given ID", $errors);
        }

        return $result;
    }
}
