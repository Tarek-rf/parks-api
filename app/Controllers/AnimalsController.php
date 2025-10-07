<?php

namespace App\Controllers;

use App\Domain\Models\AnimalsModel;

class AnimalsController extends BaseController
{
    private AnimalsModel $animals_model;

    public function __construct(AnimalsModel $animals_model) {
        $this->animals_model = $animals_model;
    }

    public function handleGetAnimals() : Response {
        $
    }


}
