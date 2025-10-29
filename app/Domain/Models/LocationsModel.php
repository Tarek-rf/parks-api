<?php

namespace App\Domain\Models;

use App\Exceptions\HttpInvalidSortingParamsException;
use App\Exceptions\HttpOutOfRangeInputException;
use App\Exceptions\HttpValidationException;
use App\Helpers\Core\PDOService;
use Psr\Http\Message\ServerRequestInterface;

class LocationsModel extends BaseModel
{
    public function __construct(PDOService $pdo)
    {
        parent::__construct($pdo);
    }

    /**
     *
     * Fetches a list of locations based on the provided filters and sorting options.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param array $filters
     * @throws \App\Exceptions\HttpValidationException
     * @throws \App\Exceptions\HttpOutOfRangeInputException
     * @throws \App\Exceptions\HttpInvalidSortingParamsException
     * @return array
     */
    public function getLocations(ServerRequestInterface $request, array $filters): array
    {
        $pdo_values = [];
        $sql = "SELECT * FROM locations WHERE 1 ";

        //Filtering and Validation
        $name = strtolower($filters['name'] ?? '');
        $country = strtolower($filters['country'] ?? '');
        $province = strtolower($filters['province'] ?? '');
        $area_min = $filters['area_min'] ?? '';
        $area_max = $filters['area_max'] ?? '';

        //filter by name
        if (isset($name) && !empty($name)) {
            if (!preg_match("/^[a-zA-Z\s'-]+$/", $name)) {
                throw new HttpValidationException($request, "Invalid name value: Should only contain letters, spaces, apostrophes, and hyphens.");
            }
            $sql .= " AND name LIKE CONCAT('%',:location_name,'%')";
            $pdo_values["location_name"] = $name;
        }

        //filter by country
        if (isset($country) && !empty($country)) {
            if (!preg_match("/^[a-zA-Z\s'-]+$/", $country)) {
                throw new HttpValidationException($request, "Invalid country value: Should only contain letters, spaces, apostrophes, and hyphens.");
            }
            $sql .= " AND country LIKE CONCAT('%',:location_country,'%')";
            $pdo_values["location_country"] = $country;
        }

        //filter by province
        if (isset($province) && !empty($province)) {
            if (!preg_match("/^[a-zA-Z\s'-]+$/", $province)) {
                throw new HttpValidationException($request, "Invalid province value: Should only contain letters, spaces, apostrophes, and hyphens.");
            }
            $sql .= " AND province LIKE CONCAT('%',:location_province,'%')";
            $pdo_values["location_province"] = $province;
        }

        //filter by area min
        if (isset($area_min) && !empty($area_min)) {
            if (!is_numeric($area_min)) {
                throw new HttpValidationException($request, "Invalid area.min value: Should be a number.");
            }
            if ($area_min < 0) {
                throw new HttpOutOfRangeInputException($request, "Invalid area.min value: Should be a positive number.");
            }
            $sql .= " AND area_km2 >= :location_area_min";
            $pdo_values["location_area_min"] = $area_min;
        }

        //filter by area max
        if (isset($area_max) && !empty($area_max)) {
            if (!is_numeric($area_max)) {
                throw new HttpValidationException($request, "Invalid area.max value: Should be a number.");
            }
            if ($area_max < 0) {
                throw new HttpOutOfRangeInputException($request, "Invalid area.max value: Should be a positive number.");
            }
            $sql .= " AND area_km2 <= :location_area_max";
            $pdo_values["location_area_max"] = $area_max;
        }

        //Sorting
        $sortBy = strtolower($filters['sort_by'] ?? 'id');
        $order = strtolower($filters['order'] ?? 'asc');
        $validSortBy = ['id', 'name', 'country', 'area'];
        $validOrder = ['asc', 'desc'];

        //Validate sort fields
        if (!in_array($sortBy, $validSortBy)) {
            throw new HttpInvalidSortingParamsException($request, "Invalid sort_by value: Should be one of " . implode(", ", $validSortBy));
        }
        //Validate sort order
        if (!in_array($order, $validOrder)) {
            throw new HttpInvalidSortingParamsException($request, "Invalid order value: Should be one of " . implode(", ", $validOrder));
        }
        $order = strtoupper($order);

        //Field mapping
        $fieldMapping = [
            'id' => 'id',
            'name' => 'name',
            'country' => 'country',
            'area' => 'area_km2'
        ];
        $actualField = $fieldMapping[$sortBy] ?? 'id';

        $sql .= " ORDER BY {$actualField} {$order}";

        // Pagination
        $locations = $this->paginate(
            $sql,
            $pdo_values
        );
        return $locations;
    }

    /**
     *
     * Fetches a single location by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getLocationById(int $id): mixed
    {
        $pdo_values = [];

        $sql = "SELECT * FROM locations WHERE id = :id";

        $pdo_values['id'] = $id;

        $location = $this->fetchSingle(
            $sql,
            $pdo_values
        );

        return $location;
    }
}
