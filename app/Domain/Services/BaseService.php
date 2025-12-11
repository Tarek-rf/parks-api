<?php

declare(strict_types=1);

namespace App\Domain\Services;

// use App\Validation\Validator;
use Frostybee\Valicomb\Validator;

/**
 * Base service class for all services.
 *
 * This class provides a base implementation for all services.
 * It is intended to be extended by specific service classes.
 */
abstract class BaseService
{
    //*NOTES: This class is not used yet. It is a placeholder for future services.
    //*NOTES: Here, you can define common methods for all services such as logging, validation, etc.
    /**
     * Validates the data passed by checking if it respects the validation rules
     * @param array $data the data to be validated
     * @param array $rules the validation rules to be respected
     */
    protected function validateInput(array $data, array $rules)
    {
        $validator = new Validator($data);
        $validator->mapManyFieldsToRules($rules);
        if (!$validator->validate()) {
            return $validator->errors();
        }
        return true;
    }
}
