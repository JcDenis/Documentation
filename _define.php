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
    'Use special templates for documentation posts and categories',
    'Jean-Christian Paul Denis and Contributors',
    '0.8.3',
    [
        'requires'    => [
            ['core', '2.36'],
        ],
        'settings'    => ['blog' => '#params.' . $this->id . '_params'],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-12-16T12:10:10+00:00',
    ]
);
