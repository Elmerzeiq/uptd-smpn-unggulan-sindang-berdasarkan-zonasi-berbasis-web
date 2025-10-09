<?php

use SimpleSoftwareIO\QrCode\Drivers\GdDriver;
use SimpleSoftwareIO\QrCode\Drivers\ImagickDriver;

return [
    'default' => 'gd',

    'drivers' => [
        'gd' => [
            'class' => GdDriver::class,
        ],
        'imagick' => [
            'class' => ImagickDriver::class,
        ],
    ],
];
