<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Razoyo\CarProfile\Api\ApiCarsRepositoryInterface;
use Razoyo\CarProfile\Api\CustomerCarRepositoryInterface;
use Razoyo\CarProfile\Api\Data\CustomerCarInterfaceFactory;
use Razoyo\CarProfile\Api\Data\CustomerCarInterface;
use Razoyo\CarProfile\Model\ResourceModel\CustomerCar as ResourceCustomerCar;

class CustomerCarRepository implements CustomerCarRepositoryInterface
{
    private const ERROR_SAVE = 'Unable to save customer car: %1';
    private const ERROR_DELETE = 'Unable to delete customer car: %1';

    /**
     * @param CustomerCarInterfaceFactory $carFactory
     * @param ResourceCustomerCar $resourceModel
     * @param ApiCarsRepositoryInterface $carsRepository
     */
    public function __construct(
        private readonly CustomerCarInterfaceFactory $carFactory,
        private readonly ResourceCustomerCar $resourceModel,
        private readonly ApiCarsRepositoryInterface $carsRepository
    ) { }

    /**
     * Save customer car profile by car ID.
     *
     * @param int $customerId
     * @param string $carId
     * @return CustomerCarInterface
     */
    public function saveByCarId(int $customerId, string $carId): CustomerCarInterface
    {
        $customerCar = $this->get($customerId);

        $apiCar = $this->carsRepository->getCarById($carId);
        $apiCar->setEntityId($customerCar->getEntityId())
            ->setCustomerId($customerId)
            ->setExtId($carId);

        return $this->save($apiCar);
    }

    /**
     * Save the customer car profile.
     *
     * @param CustomerCarInterface $customerCar
     * @return CustomerCarInterface
     * @throws CouldNotSaveException
     */
    public function save(CustomerCarInterface $customerCar): CustomerCarInterface
    {
        try {
            $this->resourceModel->save($customerCar);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                self::ERROR_SAVE,
                $exception->getMessage()
            ));
        }
        return $customerCar;
    }

    /**
     * Retrieve customer car profile by customer ID.
     *
     * @param int $customerId
     * @return CustomerCarInterface
     */
    public function get(int $customerId): CustomerCarInterface
    {
        $customerCar = $this->carFactory->create();
        $this->resourceModel->load($customerCar, $customerId, 'customer_id');
        return $customerCar;
    }

    /**
     * Delete the customer car profile.
     *
     * @param CustomerCarInterface $customerCar
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CustomerCarInterface $customerCar): bool
    {
        try {
            $this->resourceModel->delete($customerCar);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                self::ERROR_DELETE,
                $exception->getMessage()
            ));
        }
        return true;
    }
}
