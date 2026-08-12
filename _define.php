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

if (!isset($this) || !is_object($this) || !method_exists($this, 'registerModule') || !isset($this->id) || !is_string($this->id)) {
    return;
}

$this->registerModule(
    'Documentation',
    'Use special templates for documentation posts and categories',
    'Jean-Christian Paul Denis and Contributors',
    '0.10',
    [
        'requires'    => [
            ['core', '2.39'],
        ],
        'settings'    => ['blog' => '#params.' . $this->id . '_params'],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2026-08-12T20:52:08+00:00',
    ]
);
