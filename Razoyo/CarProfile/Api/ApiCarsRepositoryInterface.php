<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Api;

use Razoyo\CarProfile\Api\Data\CustomerCarInterface;

interface ApiCarsRepositoryInterface
{
    /**
     * @return array
     */
    public function getCarMakes(): array;

    /**
     * @param string $make
     * @return CustomerCarInterface[]
     */
    public function getCarsByMake(string $make): array;

    /**
     * @param string $carId
     * @return CustomerCarInterface
     */
    public function getCarById(string $carId): CustomerCarInterface;
}
