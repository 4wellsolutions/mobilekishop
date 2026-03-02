<?php

/**
 * Attribute ID constants used across the application.
 * Centralizes magic numbers from FilterService/TabletFilterController.
 *
 * Usage: config('attributes.mobile.ram') => 76
 */
return [
    'mobile' => [
        'ram' => 76,
        'rom' => 77,
        'screen_size' => 75,
        'camera_count' => 74,
        'camera_mp' => 73,
        'processor' => 34,
        'network' => 36,
        'curved_display' => 263,
        'flip' => 264,
        'folding' => 265,
        'release_date' => 80,
    ],
    'tablet' => [
        'ram' => 239,
        'rom' => 77,
        'screen' => 1,
        'camera_mp' => 5,
        '4g' => 199,
        '5g' => 200,
    ],
    'powerbank' => [
        'capacity' => 302,
    ],
    'phonecover' => [
        'model' => 312,
    ],
    'charger' => [
        'port_type' => 315,
        'wattage' => 323,
    ],
];
