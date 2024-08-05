<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Razoyo\CarProfile\Api\Data\CustomerCarInterface;
use Magento\Framework\Model\AbstractModel;
use Razoyo\CarProfile\Model\ResourceModel\CustomerCar as CustomerCarResource;

class CustomerCar extends AbstractModel implements CustomerCarInterface
{
    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(CustomerCarResource::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getModel(): ?string
    {
        return $this->getData(static::MODEL);
    }

    /**
     * {@inheritdoc}
     */
    public function setModel(string $model): CustomerCarInterface
    {
        return $this->setData(static::MODEL, $model);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerId(): ?int
    {
        return (int)$this->getData(static::CUSTOMER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId(int $customerId): CustomerCarInterface
    {
        return $this->setData(static::CUSTOMER_ID, $customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getSeats(): int
    {
        return (int) $this->getData(static::SEATS);
    }

    /**
     * {@inheritdoc}
     */
    public function setSeats(int $seats): CustomerCarInterface
    {
        return $this->setData(static::SEATS, $seats);
    }

    /**
     * {@inheritdoc}
     */
    public function getMpg(): int
    {
        return (int) $this->getData(static::MPG);
    }

    /**
     * {@inheritdoc}
     */
    public function setMpg(int $mpg): CustomerCarInterface
    {
        return $this->setData(static::MPG, $mpg);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtId(): ?string
    {
        return $this->getData(static::EXT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtId(string $extId): CustomerCarInterface
    {
        return $this->setData(static::EXT_ID, $extId);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(): float
    {
        return (float) $this->getData(static::PRICE);
    }

    /**
     * {@inheritdoc}
     */
    public function setPrice(float $price): CustomerCarInterface
    {
        return $this->setData(static::PRICE, $price);
    }

    /**
     * {@inheritdoc}
     */
    public function getYear(): int
    {
        return (int)$this->getData(static::YEAR);
    }

    /**
     * {@inheritdoc}
     */
    public function setYear(int $year): CustomerCarInterface
    {
        return $this->setData(static::YEAR, $year);
    }

    /**
     * {@inheritdoc}
     */
    public function getMake(): ?string
    {
        return $this->getData(static::MAKE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMake(string $make): CustomerCarInterface
    {
        return $this->setData(static::MAKE, $make);
    }

    /**
     * {@inheritdoc}
     */
    public function getImage(): ?string
    {
        return $this->getData(static::IMAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setImage(string $image): CustomerCarInterface
    {
        return $this->setData(static::IMAGE, $image);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityId()
    {
        return $this->getData(static::ENTITY_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntityId($id): CustomerCarInterface
    {
        return $this->setData(static::ENTITY_ID, $id);
    }
}
