<?php declare(strict_types=1);

namespace App\Builders;

use App\Models\StayModel;

/**
 * Class StayBuilder
 * @package App\Bulders
 *
 */
class StayBuilder
{

    /**
     * @param string $date
     * @param int $cityId
     * @param int $hotelId
     * @param string $roomKey
     * @param int $mealId
     * @param int $supplierId
     * @param string $roomTypeHash
     * @param float $price
     * @param string $currency
     * @return StayModel
     */
    public static function build(
        string $date,
        int $cityId,
        int $hotelId,
        string $roomKey,
        int $mealId,
        int $supplierId,
        string $roomTypeHash,
        float $price,
        string $currency): StayModel
    {
        return new StayModel(
            $date,
            $cityId,
            $hotelId,
            $roomKey,
            $mealId,
            $supplierId,
            $roomTypeHash,
            $price,
            $currency);
    }
}