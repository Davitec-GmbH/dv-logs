<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'dv_logs',
    'description' => 'Lightweight TYPO3 backend module that provides a simple interface for viewing and managing TYPO3 system log files.',
    'category' => 'module',
    'author' => 'Philipp Gollmer',
    'author_email' => 'pg@davitec.de',
    'author_company' => 'Davitec GmbH',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Davitec\\Dvlogs\\' => 'Classes',
        ],
    ],
];
