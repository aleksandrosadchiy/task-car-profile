<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Plugin;

use Magento\Customer\Api\Data\CustomerExtensionFactory;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Magento\Customer\Api\Data\CustomerInterface;

/**
 * Create customer extension attributes if there are none created
 */
class CustomerPlugin
{
    /**
     * @param CustomerExtensionFactory $extensionFactory
     */
    public function __construct(private readonly CustomerExtensionFactory $extensionFactory)
    {
    }

    /**
     * @param CustomerInterface $entity
     * @param CustomerExtensionInterface|null $extension
     * @return \Magento\Customer\Api\Data\CustomerExtension|CustomerExtensionInterface|null
     */
    public function afterGetExtensionAttributes(
        CustomerInterface          $entity,
        CustomerExtensionInterface $extension = null
    )
    {
        if ($extension === null) {
            $extension = $this->extensionFactory->create();
        }

        return $extension;
    }
}
