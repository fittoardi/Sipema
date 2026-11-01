<?php

namespace App\Services;

class GeoDistanceCalculator
{
    private const float EARTH_RADIUS_METERS = 6371000.0;

    /**
     * Calculate the great-circle distance between two points on Earth using the Haversine formula.
     *
     * @return float Distance in meters
     */
    public function calculate(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round(self::EARTH_RADIUS_METERS * $c, 2);
    }

    /**
     * Determine if a user coordinate is within a specified radius from a target location.
     */
    public function isWithinRadius(
        float $userLat,
        float $userLon,
        float $targetLat,
        float $targetLon,
        float $radiusMeters,
        float $toleranceMeters = 0.0
    ): bool {
        $distance = $this->calculate($userLat, $userLon, $targetLat, $targetLon);

        return $distance <= ($radiusMeters + $toleranceMeters);
    }
}
