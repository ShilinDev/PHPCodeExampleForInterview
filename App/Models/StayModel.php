<?php declare(strict_types=1);

namespace App\Models;

class StayModel implements \Serializable
{
    /**
     * @var string
     */
    private $date;
    /**
     * @var int
     */
    private $cityId;
    /**
     * @var int
     */
    private $hotelId;
    /**
     * @var string
     */
    private $roomKey;
    /**
     * @var int
     */
    private $mealId;
    /**
     * @var int
     */
    private $supplierId;
    /**
     * @var string
     */
    private $roomTypeHash;
    /**
     * @var float
     */
    private $price;
    /**
     * @var string
     */
    private $currency;

    /**
     * StayModel constructor.
     * @param string $date
     * @param int $cityId
     * @param int $hotelId
     * @param string $roomKey
     * @param int $mealId
     * @param int $supplierId
     * @param string $roomTypeHash
     * @param float $price
     * @param string $currency
     */
    public function __construct(
        string $date,
        int $cityId,
        int $hotelId,
        string $roomKey,
        int $mealId,
        int $supplierId,
        string $roomTypeHash,
        float $price,
        string $currency)
    {
        $this->date = $date;
        $this->cityId = $cityId;
        $this->hotelId = $hotelId;
        $this->roomKey = $roomKey;
        $this->mealId = $mealId;
        $this->supplierId = $supplierId;
        $this->roomTypeHash = $roomTypeHash;
        $this->price = $price;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getCityId(): int
    {
        return $this->cityId;
    }

    /**
     * @return int
     */
    public function getHotelId(): int
    {
        return $this->hotelId;
    }

    /**
     * @return string
     */
    public function getRoomKey(): string
    {
        return $this->roomKey;
    }

    /**
     * @return int
     */
    public function getMealId(): int
    {
        return $this->mealId;
    }

    /**
     * @return int
     */
    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    /**
     * @return string
     */
    public function getRoomTypeHash(): string
    {
        return $this->roomTypeHash;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Just for Review Example
     */
    public function serialize(): string
    {
        return $this->cityId . ' ' . $this->hotelId . ' ' . $this->roomTypeHash;
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized):void
    {
        return;
    }


}