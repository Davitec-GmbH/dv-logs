<?php

declare(strict_types=1);

$EM_CONF['dv_logs'] = [
    'title' => 'DV Logs',
    'description' => 'Lightweight TYPO3 backend module that provides a simple interface for viewing and managing TYPO3 system log files.',
    'category' => 'module',
    'author' => 'Davitec GmbH',
    'author_email' => 'devops@davitec.de',
    'author_company' => 'Davitec GmbH, +Pluswerk Standort Dresden',
    'state' => 'stable',
    'version' => '1.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-14.3.99',
            'php' => '8.2.0-8.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
