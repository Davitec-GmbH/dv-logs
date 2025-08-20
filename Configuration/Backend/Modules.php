<?php

use Davitec\Dvlogs\Controller\LogModuleController;

return [
    'dvlogs_logmodule' => [
        'parent' => 'system',
        'position' => ['after' => 'log'],
        'access' => 'admin',
        'iconIdentifier' => 'ext-dv_logs_module',
        'labels' => 'LLL:EXT:dv_logs/Resources/Private/Language/locallang_mod.xlf',
        'path' => '/module/system/dvlogs-logmodule',
        'extensionName' => 'DvLogs',
        'controllerActions' => [
            LogModuleController::class => ['list', 'show', 'delete'],
        ],
    ],
];
