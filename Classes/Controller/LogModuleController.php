<?php

namespace Davitec\Dvlogs\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class LogModuleController extends ActionController
{
    protected ModuleTemplate $moduleTemplate;

    public function __construct(private readonly ModuleTemplateFactory $moduleTemplateFactory) {}

    protected function initializeAction(): void
    {
        $this->moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        parent::initializeAction();
    }

    public function listAction(): ResponseInterface
    {
        $this->moduleTemplate->assign('logFiles', $this->getLogFiles());
        return $this->moduleTemplate->renderResponse('LogModule/List');
    }

    public function showAction(string $fileName): ResponseInterface
    {
        $logFiles = $this->getLogFiles();
        $parsedLogEntries = [];

        if (in_array($fileName, $logFiles, true)) {
            $logPath = self::getLogDirectory() . $fileName;
            if (is_readable($logPath)) {
                $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $lines = array_slice($lines, -500);
                $parsedLogEntries = $this->parseLogContent($lines);
            }
        }

        $this->moduleTemplate->assignMultiple([
            'logFiles' => $logFiles,
            'activeFile' => $fileName,
            'logEntries' => $parsedLogEntries,
        ]);

        return $this->moduleTemplate->renderResponse('LogModule/Show');
    }

    public function deleteAction(string $fileName): ResponseInterface
    {
        $path = self::getLogDirectory() . $fileName;
        if (in_array($fileName, $this->getLogFiles(), true) && is_file($path) && is_writable($path)) {
            unlink($path);
        }
        return $this->redirect(actionName: 'list');
    }

    protected function getLogFiles(): array
    {
        $logDir = self::getLogDirectory();
        $logFiles = [];

        if (is_dir($logDir)) {
            foreach (scandir($logDir) as $file) {
                if (is_file($logDir . $file)) {
                    $logFiles[] = $file;
                }
            }
        }

        return $logFiles;
    }

    protected function parseLogContent(array $lines): array
    {
        $entries = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            if (preg_match('/^(?<datetime>\w{3}, \d{2} \w{3} \d{4} \d{2}:\d{2}:\d{2}) .*? \[(?<level>[A-Z]+)\] (?<message>.*)$/', $line, $matches)) {
                $entries[] = [
                    'datetime' => $matches['datetime'],
                    'level' => strtolower($matches['level']),
                    'message' => $matches['message']
                ];
            } elseif (preg_match('/component="(?<component>[^"]+)"[^\\[]+\[(?<level>[A-Z]+)\](.*)$/', $line, $matches)) {
                $entries[] = [
                    'datetime' => '',
                    'level' => strtolower($matches['level']),
                    'message' => $line
                ];
            } else {
                $entries[] = [
                    'datetime' => '',
                    'level' => '',
                    'message' => $line
                ];
            }
        }

        return $entries;
    }

    protected static function getLogDirectory(): string
    {
        return Environment::getVarPath() . '/log/';
    }
}
