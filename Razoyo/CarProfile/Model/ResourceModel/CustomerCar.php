<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerCar extends AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init('customer_car', 'entity_id');
    }
}
