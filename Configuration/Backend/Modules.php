<?php

use Davitec\Dvlogs\Controller\LogModuleController;

return [
    'dv_logs' => [
        'parent' => 'system',
        'position' => ['after' => 'log'],
        'access' => 'admin',
        'iconIdentifier' => 'ext-dv_logs_module',
        'labels' => 'LLL:EXT:dv_logs/Resources/Private/Language/locallang_mod.xlf',
        'path' => '/module/system/dv-logs',
        'extensionName' => 'DvLogs',
        'controllerActions' => [
            LogModuleController::class => ['list', 'show', 'delete'],
        ],
    ],
];
