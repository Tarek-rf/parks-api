<?php

namespace App\Domain\Services;

use App\Domain\Models\AnimalsModel;
use App\Helpers\Core\Result;
use App\Validation\Validator;

class AnimalsService
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

        $validator = new Validator($new_animals[0]);

        $validator->mapFieldsRules($rules);

        if ($validator->validate()) {
            $last_inserted_id = $this->animals_model->createAnimal($new_animals[0]);

            // returning a successful operation
            $result = Result::success("The animal was created successfully!", ["last_inserted_id" => $last_inserted_id]);
        } else {
            $errors[] = $validator->errors();
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
                array('in', array("carnivore", "cerbivore", "omnivore"))
            ],
            'phylum' => [
                array('lengthBetween', 2, 80)
            ],

        );

        $validator = new Validator($update_animal[0]);

        $validator->mapFieldsRules($rules);

        if ($validator->validate()) {
            $last_updated_id = $this->animals_model->updateAnimal($update_animal[0], $condition);

            $result = Result::success("The animal was updated successfully!", ["last_updated_id" => $last_updated_id]);
        } else {
            $errors[] = $validator->errors();
            $result = Result::failure("The animal could not be updated with the given ID", $errors);
        }

        return $result;
    }

    public function doDeleteAnimal(array $condition): Result
    {

        $rules = [
            "id" => [
                'required',
                'integer'
            ]
        ];

        $validator = new Validator($condition);

        $validator->mapFieldsRules($rules);

        if ($validator->validate()) {
            $last_deleted_id = $this->animals_model->deleteAnimal($condition);

            $result = Result::success("The animal was deleted successfully", ["last_deleted_id" => $last_deleted_id]);
        } else {
            $errors[] = $validator->errors();
            $result = Result::failure("Could not delete the animal with the given ID", $errors);
        }

        return $result;
    }
}
