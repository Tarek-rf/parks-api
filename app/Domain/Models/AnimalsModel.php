<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use InvalidArgumentException;

class AnimalsModel extends BaseModel
{

    public function __construct(PDOService $pDOService)
    {
        parent::__construct($pDOService);
    }

    /**
     * Gets the animal collection from the database and allows filtering and sorting.
     * @param array $filters Filters
     * @return array List of animals
     */
    public function getAnimals(array $filters): array
    {
        $pdo_values = [];

        $query = "SELECT * FROM animals WHERE 1 ";

        //filter by conservation status
        if (isset($filters["conservation"]) && !empty($filters["conservation"])) {
            $query .= " AND conservation_status LIKE CONCAT('%', :animal_conservation, '%')";
            $pdo_values["animal_conservation"] = $filters["conservation"];
        }

        //filter by diet
        if (isset($filters["diet"]) && !empty($filters["diet"])) {
            $query .= " AND diet LIKE CONCAT('%', :animal_diet, '%')";
            $pdo_values["animal_diet"] = $filters["diet"];
        }


        //filter by family name
        if (isset($filters["family"]) && !empty($filters["family"])) {
            $query .= " AND family_name LIKE CONCAT('%', :animal_family, '%')";
            $pdo_values["animal_family"] = $filters["family"];
        }

        if (isset($filters["sort_by"]) && !empty($filters["sort_by"]) && isset($filters["order"]) && !empty($filters["order"])) {
            $query .= " ORDER BY {$filters["sort_by"]} {$filters["order"]}";
        }


        $animals = $this->paginate($query, $pdo_values);

        return $animals;
    }

    /**
     * Fetches a single animal based off the animal id.
     * @param int $id Id
     * @return mixed An animal
     */
    /*
    public function getAnimalById(int $id): mixed
    {

        $pdo_values = [];

        $query = "SELECT * FROM animals WHERE 1";
        if (isset($id) && !empty($id)) {
            $query .= " AND id = :animal_id"; //prevent sql injections
            $pdo_values = ['animal_id' => $id];
        }

        $animal = $this->fetchSingle(
            $query,
            $pdo_values
        );

        return $animal;
    }
    */

    public function createAnimal(array $new_animal): int
    {

        return $this->insert("animals", $new_animal);
    }

    public function updateAnimal(array $updated_animal, array $condition): int
    {

        return $this->update("animals", $updated_animal, $condition);
    }

    public function deleteAnimal(array $condition): int
    {

        return $this->delete("animals", $condition);
    }
}
