<?php

use App\Services\JadwalConflictDetector;

it('detects overlapping time intervals correctly', function () {
    $detector = new JadwalConflictDetector;

    // Overlapping intervals: 08:00 - 10:00 and 09:00 - 11:00
    expect($detector->hasTimeOverlap('08:00', '10:00', '09:00', '11:00'))->toBeTrue();

    // Enclosed interval: 08:00 - 12:00 and 09:00 - 10:00
    expect($detector->hasTimeOverlap('08:00', '12:00', '09:00', '10:00'))->toBeTrue();

    // Adjacent non-overlapping: 08:00 - 10:00 and 10:00 - 12:00
    expect($detector->hasTimeOverlap('08:00', '10:00', '10:00', '12:00'))->toBeFalse();

    // Distinct non-overlapping: 08:00 - 10:00 and 13:00 - 15:00
    expect($detector->hasTimeOverlap('08:00', '10:00', '13:00', '15:00'))->toBeFalse();
});
