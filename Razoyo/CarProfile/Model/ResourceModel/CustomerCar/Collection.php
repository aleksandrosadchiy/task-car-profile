<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Model\ResourceModel\CustomerCar;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Razoyo\CarProfile\Model\CustomerCar;
use Razoyo\CarProfile\Model\ResourceModel\CustomerCar as CustomerCarResource;

class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            CustomerCar::class,
            CustomerCarResource::class
        );
    }
}
