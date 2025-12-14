<?php

namespace App\Domain\Services;

use App\Domain\Models\UserModel;
use App\Helpers\Core\PasswordTrait;
use App\Helpers\Core\Result;

class UserAuthService extends BaseService
{

    use PasswordTrait;
    public function __construct(private UserModel $user_model) {}

    /**
     * validates the registration of a user
     * @param array $data the data to validate
     * @return Result the result 
     */
    function validateRegisterUser(array $data): Result
    {
        $rules = array(
            'first_name' => array(
                'required',
                array('lengthBetween', 3, 100),
            ),
            'last_name' => array(
                'required',
                array('lengthBetween', 3, 100),
            ),
            'password' => array(
                'required',
                array('lengthBetween', 6, 140)
            ),
            'email' => [
                'email',
                'required'
            ],
            'role' => [
                'required',
                array('in', array("admin", "user"))
            ],
        );

        $valid = $this->validateInput($data, $rules);

        if ($valid === true) {
            $data["password"] = $this->cryptPassword($data["password"]);
            $last_inserted_id = $this->user_model->createUser($data);

            // returning a successful operation
            $result = Result::success("The user was created successfully!", ["last_inserted_id" => $last_inserted_id]);
        } else {
            $errors[] = $valid;
            $result = Result::failure("The user was not created", $errors);
        }
        return $result;
    }
}
