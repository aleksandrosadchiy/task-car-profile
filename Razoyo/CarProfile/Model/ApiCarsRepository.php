<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Framework\Exception\LocalizedException;
use Razoyo\CarProfile\Api\ApiCarsRepositoryInterface;
use Razoyo\CarProfile\Api\Data\CustomerCarInterface;
use Razoyo\CarProfile\Model\Service\ApiData;
use Magento\Framework\Api\DataObjectHelper;
use Razoyo\CarProfile\Api\Data\CustomerCarInterfaceFactory;
use Razoyo\CarProfile\Model\Service\ConfigProvider;

class ApiCarsRepository implements ApiCarsRepositoryInterface
{
    private const FILTER_BY_MAKE = 'make';
    private const URI_KEY_MAKES = 'makes';
    private const URI_KEY_CARS = 'cars';
    private const URI_KEY_CAR = 'car';
    private const BASE_URI = 'cars';
    private const ERROR_CAR_MAKE_FETCH = 'Unable to fetch car makes.';
    private const ERROR_CAR_BY_MAKE_FETCH = 'Unable to fetch cars by make.';
    private const ERROR_CAR_BY_ID_FETCH = 'Unable to fetch car by ID.';
    private const ERROR_REFRESH_TOKEN = 'Failed to refresh token.';

    /**
     * Cached list of car makes
     * @var array
     */
    private array $cachedCarMakes = [];

    /**
     * Cached list of car models by makes
     * @var array
     */
    private array $cachedCarModelsByMakes = [];

    /**
     * Cached list of car information by IDs
     * @var array
     */
    private array $cachedCarInfoByIds = [];

    /**
     * @param CustomerCarInterfaceFactory $carFactory
     * @param ConfigProvider $configProvider
     * @param ApiData $apiClient
     * @param DataObjectHelper $dataHelper
     */
    public function __construct(
        private readonly CustomerCarInterfaceFactory $carFactory,
        private readonly ConfigProvider $configProvider,
        private readonly ApiData $apiClient,
        private readonly DataObjectHelper $dataHelper,
    ) {}

    /**
     * Get API Url from configuration
     * @return string
     */
    private function getServiceUrl(): string
    {
        return $this->configProvider->getServiceUrl() . static::BASE_URI;
    }

    /**
     * Refresh token in case it expired
     * @return void
     * @throws LocalizedException
     */
    protected function refreshToken(): void
    {
        if (!$this->apiClient->isTokenValid()) {
            $this->getCarMakes();
        } else {
            throw new LocalizedException(__('Failed to refresh token.'));
        }
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getCarMakes(): array
    {
        $params = [static::FILTER_BY_MAKE => 'non-existing-make'];
        if (empty($this->cachedCarMakes)) {
            try {
                $response = $this->apiClient->execute($this->getServiceUrl(), $params);
                if (array_key_exists(static::URI_KEY_MAKES, $response)) {
                    $this->cachedCarMakes = $response[static::URI_KEY_MAKES];
                }
            } catch (\Exception $e) {
                throw new LocalizedException(__(static::ERROR_CAR_MAKE_FETCH));
            }
        }

        return $this->cachedCarMakes;
    }

    public function getCarsByMake(string $make): array
    {
        $params = [static::FILTER_BY_MAKE => $make];

        if (!isset($this->cachedCarModelsByMakes[$make]) || empty($this->cachedCarModelsByMakes[$make])) {
            try {
                $response = $this->apiClient->execute($this->getServiceUrl(), $params);
                foreach ($response[static::URI_KEY_CARS] as $car) {
                    $this->cachedCarModelsByMakes[$make][] = $car;
                }
            } catch (\Exception $e) {
                throw new LocalizedException(__(static::ERROR_CAR_BY_MAKE_FETCH));
            }
        }

        $cars = [];

        foreach ($this->cachedCarModelsByMakes[$make] as $car) {
            $customerCar = $this->carFactory->create();
            $this->dataHelper->populateWithArray(
                $customerCar,
                $car,
                CustomerCarInterface::class
            );
            $cars[] = $customerCar;
        }

        return $cars;
    }

    /**
     * Get car information by a car ID
     * @param string $carId
     * @return CustomerCarInterface
     * @throws LocalizedException
     */
    public function getCarById(string $carId): CustomerCarInterface
    {
        $url = $this->getServiceUrl() . '/' . $carId;

        if (!isset($this->cachedCarInfoByIds[$carId])) {
            try {
                $this->refreshToken();
                $response = $this->apiClient->execute($url, authRequired: true);
                $this->cachedCarInfoByIds[$carId] = $response[static::URI_KEY_CAR];
            } catch (\Exception $e) {
                throw new LocalizedException(__(static::ERROR_CAR_BY_ID_FETCH));
            }
        }

        $customerCar = $this->carFactory->create();
        $this->dataHelper->populateWithArray(
            $customerCar,
            $this->cachedCarInfoByIds[$carId],
            CustomerCarInterface::class
        );

        return $customerCar;
    }
}
