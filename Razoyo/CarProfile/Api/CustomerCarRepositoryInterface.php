<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Api;

use Razoyo\CarProfile\Api\Data\CustomerCarInterface;

/**
 * Customer car extension attribute repository
 */
interface CustomerCarRepositoryInterface
{
    /**
     * @param CustomerCarInterface $customerCar
     * @return CustomerCarInterface
     */
    public function save(CustomerCarInterface $customerCar): CustomerCarInterface;

    /**
     * @param int $customerId
     * @return CustomerCarInterface
     */
    public function get(int $customerId): CustomerCarInterface;

    /**
     * @param CustomerCarInterface $customerCar
     * @return bool
     */
    public function delete(CustomerCarInterface $customerCar): bool;

    /**
     * @param int $customerId
     * @param string $carId
     * @return CustomerCarInterface
     */
    public function saveByCarId(int $customerId, string $carId): CustomerCarInterface;
}
