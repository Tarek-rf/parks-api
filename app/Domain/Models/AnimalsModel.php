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

        $query = "SELECT a.* FROM animals a WHERE 1 ";

        //filter by conservation status
        if (isset($filters["conservation"]) && !empty($filters["conservation"])) {
            $query .= " AND conservation_status LIKE CONCAT('%', :animal_conservation, '%')";
            $pdo_values["animal_conservation"] = $filters["conservation"];
        }

        //filter by diet
        if (isset($filters["diet"]) && !empty($filters["diet"])) {
            $query .= " AND diet LIKE CONCAT('%', :animal_diet , '%')";
            $pdo_values["animal_diet "] = $filters["diet"];
        }

        //filter by family name
        if (isset($filters["family"]) && !empty($filters["family"])) {
            $query .= " AND family_name LIKE CONCAT('%', :animal_family, '%')";
            $pdo_values["animal_family"] = $filters["family"];
        }

        $validSortByFields = ['common_name', 'average_weight_kg'];
        $validOrders = ['asc', 'desc'];

        $sortBy = $filters['sort_by'] ?? 'id';
        $order = $filters['order'] ?? 'asc';

        if (!in_array($sortBy, $validSortByFields)) {
            throw new InvalidArgumentException("Invalid sort field: {$sortBy}");
        }

        if (!in_array(strtolower($order), $validOrders)) {
            throw new InvalidArgumentException("Invalid sort order: {$order}");
        }

        $order = strtoupper($order); // Ensure consistent case

        $fieldMapping = [
            'common_name' => 'common_name',
            'average_weight_kg' => 'average_weight_kg',
        ];

        $actualField = $fieldMapping[$sortBy] ?? 'id';
        $query .= " ORDER BY {$actualField} {$order}";

        $animals = $this->paginate($query, $pdo_values);

        return $animals;
    }

    /**
     * Fetches a single animal based off the animal id.
     * @param int $id Id
     * @return mixed An animal
     */
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
}
