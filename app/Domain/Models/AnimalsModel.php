<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;
use InvalidArgumentException;

class AnimalsModel extends BaseModel
{
    /**
     * Summary of __construct
     * @param \App\Helpers\Core\PDOService $pDOService
     */
    public function __construct(PDOService $pDOService)
    {
        parent::__construct($pDOService);
    }

    /**
     * Summary of getAnimals (Collection and filters)
     * @param array $filters
     * @return array
     */
    public function getAnimals(array $filters): array
    {
        $pdo_values = [];

        $query = "SELECT * FROM animals a WHERE 1 ";

        //filter by conservation status
        if (isset($filters["conservation"]) && !empty($filters["conservation"])) {
            $query .= " AND a.conservation_status LIKE CONCAT('%', :animal_conservation, '%')";
            $pdo_values["animal_conservation"] = $filters["conservation"];
        }

        //filter by diet
        if (isset($filters["diet"]) && !empty($filters["diet"])) {
            $query .= " AND a.diet LIKE CONCAT('%', :animal_diet , '%')";
            $pdo_values["animal_diet "] = $filters["diet"];
        }

        //filter by family name
        if (isset($filters["family"]) && !empty($filters["family"])) {
            $query .= " AND a.family_name LIKE CONCAT('%', :animal_family, '%')";
            $pdo_values["animal_family"] = $filters["family"];
        }

        $validSortByFields = ['common_name', 'average_weight_kg'];
        $validOrders = ['asc', 'desc'];

        $sortBy = $filters['sort_by'] ?? 'animal_id';
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

        $actualField = $fieldMapping[$sortBy] ?? 'keyboard_id';
        $query .= " ORDER BY a.{$actualField} {$order}";

        $animals = $this->paginate($query, $pdo_values);

        return $animals;
    }

    /**
     * Summary of getAnimalById
     * @param int $id
     * @return void
     */
    public function getAnimalById(int $id): mixed
    {

        $pdo_values = [];

        $query = "SELECT * FROM animals WHERE 1";
        if (isset($id) && $id >= 1) {
            $query .= " AND animal_id = :$id"; //prevent sql injections
            $pdo_values["animal_id"] = $id;
        }

        $animal = $this->fetchAll(
            $query,
            $pdo_values
        );

        return $animal;
    }
}
