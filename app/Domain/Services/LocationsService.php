<?php

namespace App\Domain\Services;

use App\Domain\Models\LocationsModel;
use App\Helpers\Core\Result;
use App\Validation\Validator;

class LocationsService
{
    public function __construct(private LocationsModel $locations_model) {}

    /**
     *
     * Validates arguments of createLocation method in LocationModel and calls the method to execute
     *
     * @param array $new_locations values for location to be created
     * @return Result
     */
    public function doCreateLocation(array $new_locations): Result
    {
        //* Validate the fields of the new item to be added to the collection.
        $rules = [
            "name" => [
                'required',
                ['lengthMax', 120],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "country" => [
                'required',
                ['lengthMax', 80],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "province" => [
                ['lengthMax', 80],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "address" => [
                ['lengthMax', 200],
                ['regex', "/^[a-zA-Z0-9\s'-,]+$/"]
            ],
            "latitude" => [
                'numeric'
            ],
            "longitude" => [
                'numeric'
            ],
            "area_km2" => [
                'numeric',
                ['min', 0],
                // ['max', 999999]
            ],
            "max_elevation_m" => [
                'integer',
                ['min', 0],
                // ['max', 999999]
            ],
            "continent" => [
                ['lengthMax', 30],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "website" => [
                'url',
                ['lengthMax', 200]
            ]

        ];

        $validator = new Validator($new_locations[0]);
        // Important: map the validation rules before calling validate()
        $validator->mapFieldsRules($rules);
        //* If the fields are valid -> Insert them into the DB
        if ($validator->validate()) {

            $last_inserted_id = $this->locations_model->createLocation($new_locations[0]);

            //* Returning a successful operation
            $result = Result::success("The location was created successfully", ["last inserted id" => $last_inserted_id]);
        } else {
            //* Returning a failed operation
            $errors[] = $validator->errors();
            $result = Result::failure("The location could not be created", $errors);
        }
        return $result;
    }
    /**
     *
     * Validates arguments of deleteLocations method in LocationModel and calls the method to execute
     *
     * @param array $location_ids Location ids to be deleted
     * @return Result
     */
    public function doDeleteLocation(array $location_ids): Result
    {
        //* Validate the fields of the new item to be added to the collection.
        $rules = [
            "id" => [
                'required',
                'integer'
                // ['min', 0]
            ]
        ];

        $affected_rows = 0;
        foreach ($location_ids as $key => $location_id) {
            $location_id = ['id' => $location_id];
            $validator = new Validator($location_id);
            // Important: map the validation rules before calling validate()
            $validator->mapFieldsRules($rules);
            //* If the fields are valid -> Delete the record from DB
            if ($validator->validate()) {
                $affected_rows += $this->locations_model->deleteLocations($location_id['id']);
                if ($affected_rows === 0) {
                    $result = Result::failure("No locations were deleted", [
                        'message' => 'No locations were deleted. Location IDs do not exist in the DB'
                    ]);
                } else {
                    //* Returning a successful operation
                    $result = Result::success("The location was deleted successfully", ["affected rows" => $affected_rows]);
                }
            } else {
                //* Returning a failed operation
                $errors[] = $validator->errors();
                $result = Result::failure("The location could not be deleted", $errors);
            }
        }

        return $result;
    }
    /**
     *
     * Validates arguments of updateLocation method in LocationModel and calls the method to execute
     *
     * @param array $data the new values of the Location to be updated
     * @param array $where_condition the id of the Location to be updated
     * @return Result
     */
    public function doUpdateLocation(array $data, array $where_condition): Result
    {
        //* Validate the fields of the new item to be added to the collection.
        $rules = [
            "name" => [
                'required',
                ['lengthMax', 120],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "country" => [
                'required',
                ['lengthMax', 80],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "province" => [
                ['lengthMax', 80],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "address" => [
                ['lengthMax', 200],
                ['regex', "/^[a-zA-Z0-9\s'-,]+$/"]
            ],
            "latitude" => [
                'numeric'
            ],
            "longitude" => [
                'numeric'
            ],
            "area_km2" => [
                'numeric'
            ],
            "max_elevation_m" => [
                'integer'
            ],
            "continent" => [
                ['lengthMax', 30],
                ['regex', "/^[a-zA-Z\s'-]+$/"]
            ],
            "website" => [
                'url',
                ['lengthMax', 200]
            ]

        ];

        $validator = new Validator($data[0]);
        // Important: map the validation rules before calling validate()
        $validator->mapFieldsRules($rules);
        //* If the fields are valid -> update the record in the DB
        if ($validator->validate()) {
            $affected_rows = $this->locations_model->updateLocation($data[0], $where_condition);
            //* Returning a successful operation
            $result = Result::success("The location was updated successfully", ["affected rows" => $affected_rows]);
        } else {
            //* Returning a failed operation
            $errors[] = $validator->errors();
            $result = Result::failure("The location could not be updated", $errors);
        }

        return $result;
    }
}
