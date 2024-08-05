<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerSearchResultsInterface;
use Razoyo\CarProfile\Api\CustomerCarRepositoryInterface;
use Razoyo\CarProfile\Model\Service\ConfigProvider;

class CustomerRepositoryPlugin
{
    /**
     * @param CustomerCarRepositoryInterface $carRepository
     * @param ConfigProvider $config
     */
    public function __construct(
        private readonly CustomerCarRepositoryInterface $carRepository,
        private readonly ConfigProvider $config
    ) {}

    /**
     * Add car extension attribute after customer get.
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     */
    public function afterGet(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result
    ): CustomerInterface {
        if (!$this->isFeatureEnabled()) {
            return $result;
        }

        $customerCar = $this->carRepository->get((int)$result->getId());
        if ($customerCar->getId()) {
            $extensionAttributes = $result->getExtensionAttributes();
            $extensionAttributes->setData('customer_car', $customerCar);
            $result->setExtensionAttributes($extensionAttributes);
        }

        return $result;
    }

    /**
     * Add car extension attribute after customer getById.
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     */
    public function afterGetById(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result
    ): CustomerInterface {
        if (!$this->isFeatureEnabled()) {
            return $result;
        }

        $customerCar = $this->carRepository->get((int)$result->getId());
        if ($customerCar->getId()) {
            $extensionAttributes = $result->getExtensionAttributes();
            $extensionAttributes->setData('customer_car', $customerCar);
            $result->setExtensionAttributes($extensionAttributes);
        }

        return $result;
    }

    /**
     * Add car extension attribute after customer getList.
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerSearchResultsInterface $searchResults
     * @return CustomerSearchResultsInterface
     */
    public function afterGetList(
        CustomerRepositoryInterface $subject,
        CustomerSearchResultsInterface $searchResults
    ): CustomerSearchResultsInterface {
        if (!$this->isFeatureEnabled()) {
            return $searchResults;
        }

        $customers = [];
        foreach ($searchResults->getItems() as $entity) {
            $customerCar = $this->carRepository->get((int)$entity->getId());
            if ($customerCar->getId()) {
                $extensionAttributes = $entity->getExtensionAttributes();
                $extensionAttributes->setData('customer_car', $customerCar);
                $entity->setExtensionAttributes($extensionAttributes);
            }
            $customers[] = $entity;
        }
        $searchResults->setItems($customers);
        return $searchResults;
    }

    /**
     * Check if the feature is enabled.
     *
     * @return bool
     */
    private function isFeatureEnabled(): bool
    {
        return $this->config->getStatus();
    }
}
