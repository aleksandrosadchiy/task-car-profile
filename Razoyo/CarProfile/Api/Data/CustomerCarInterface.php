<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Api\Data;
interface CustomerCarInterface
{
    public const CUSTOMER_ID = 'customer_id';
    public const ENTITY_ID = 'entity_id';
    public const EXT_ID = 'ext_id';
    public const YEAR = 'year';
    public const MAKE = 'make';
    public const MODEL = 'model';
    public const PRICE = 'price';
    public const SEATS = 'seats';
    public const MPG = 'mpg';
    public const IMAGE = 'image';

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int;

    /**
     * @param int $customerId
     * @return CustomerCarInterface
     */
    public function setCustomerId(int $customerId): CustomerCarInterface;

    /**
     * @return string
     */
    public function getEntityId();

    /**
     * @param string $id
     * @return CustomerCarInterface
     */
    public function setEntityId(string $id): CustomerCarInterface;

    /**
     * @return ?string
     */
    public function getExtId(): ?string;

    /**
     * @param string $extId
     * @return CustomerCarInterface
     */
    public function setExtId(string $extId): CustomerCarInterface;

    /**
     * @return int
     */
    public function getYear(): int;

    /**
     * @param int $year
     * @return CustomerCarInterface
     */
    public function setYear(int $year): CustomerCarInterface;

    /**
     * @return ?string
     */
    public function getMake(): ?string;

    /**
     * @param string $make
     * @return CustomerCarInterface
     */
    public function setMake(string $make): CustomerCarInterface;

    /**
     * @return ?string
     */
    public function getModel(): ?string;

    /**
     * @param string $model
     * @return CustomerCarInterface
     */
    public function setModel(string $model): CustomerCarInterface;

    /**
     * @return float
     */
    public function getPrice(): float;

    /**
     * @param float $price
     * @return CustomerCarInterface
     */
    public function setPrice(float $price): CustomerCarInterface;

    /**
     * @return int
     */
    public function getSeats(): int;

    /**
     * @param int $seats
     * @return CustomerCarInterface
     */
    public function setSeats(int $seats): CustomerCarInterface;

    /**
     * @return int
     */
    public function getMpg(): int;

    /**
     * @param int $mpg
     * @return CustomerCarInterface
     */
    public function setMpg(int $mpg): CustomerCarInterface;

    /**
     * @return ?string
     */
    public function getImage(): ?string;

    /**
     * @param string $image
     * @return CustomerCarInterface
     */
    public function setImage(string $image): CustomerCarInterface;
}
