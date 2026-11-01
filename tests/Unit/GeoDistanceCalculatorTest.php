<?php

use App\Services\GeoDistanceCalculator;

it('calculates zero distance for identical coordinates', function () {
    $calculator = new GeoDistanceCalculator;
    $dist = $calculator->calculate(-6.200000, 106.816666, -6.200000, 106.816666);

    expect($dist)->toBe(0.0);
});

it('calculates correct approximate distance between two known coordinates', function () {
    $calculator = new GeoDistanceCalculator;
    // Monas (-6.175392, 106.827153) to Bundaran HI (-6.195000, 106.823056) ~2.2 km
    $dist = $calculator->calculate(-6.175392, 106.827153, -6.195000, 106.823056);

    expect($dist)->toBeGreaterThan(2000.0)
        ->toBeLessThan(2500.0);
});

it('determines if user is within radius', function () {
    $calculator = new GeoDistanceCalculator;
    $targetLat = -6.200000;
    $targetLon = 106.816666;

    // Very close (~11 meters)
    $userLat = -6.200100;
    $userLon = 106.816666;

    expect($calculator->isWithinRadius($userLat, $userLon, $targetLat, $targetLon, 50))->toBeTrue();
    expect($calculator->isWithinRadius($userLat, $userLon, $targetLat, $targetLon, 5))->toBeFalse();
});

