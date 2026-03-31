<?php

$EM_CONF['dv_logs'] = [
    'title' => 'dv_logs',
    'description' => 'Lightweight TYPO3 backend module that provides a simple interface for viewing and managing TYPO3 system log files.',
    'category' => 'module',
    'author' => 'Davitec GmbH, +Pluswerk Standort Dresden',
    'author_email' => 'devops@davitec.de',
    'author_company' => 'Davitec GmbH',
    'state' => 'stable',
    'version' => '1.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.0-14.3.99'
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
