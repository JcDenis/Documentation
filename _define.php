<?php

/**
 * @file
 * @brief       The plugin Documentation definition
 * @ingroup     Documentation
 *
 * @defgroup    Documentation Plugin Documentation.
 *
 * Use special templates for documentaion posts and categories.
 *
 * @author      Jean-Christian Paul Denis
 * @copyright   AGPL-3.0
 */
declare(strict_types=1);

$this->registerModule(
    'Documentation',
    'Use special templates for documentaion posts and categories',
    'Jean-Christian Paul Denis and Contributors',
    '0.5',
    [
        'requires'    => [
            ['core', '2.34'],
        ],
        'settings'    => ['blog' => '#params.' . $this->id . '_params'],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-05-28T17:11:14+00:00',
    ]
);
