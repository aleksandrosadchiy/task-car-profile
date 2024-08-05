<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Model\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Configuration provider
 */
class ConfigProvider
{
    private const XML_PATH_STATUS = 'razoyo/car_profile/status';
    private const XML_PATH_API_URL = 'razoyo/car_profile/api_url';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StoreManagerInterface $storeManager
    ) { }

    /**
     * @return string
     */
    public function getServiceUrl(): string
    {
        return $this->getValue( static::XML_PATH_API_URL);
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return (bool)$this->getValue( static::XML_PATH_STATUS);
    }

    /**
     * @param string $path
     * @return string|null
     */
    private function getValue(string $path): ?string
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStoreId(): int
    {
        return (int) $this->storeManager->getStore()->getId();
    }
}
