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
        $this->moduleTemplate->assign('logFiles', $this->getLogFilesMeta());
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


    /**
     * Returns all log file names from the TYPO3 /var/log directory as an array of strings.
     */
    private function getLogFiles(): array
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
    /**
     * Returns all log files from the TYPO3 /var/log directory
     * with additional details for each file:
     *  - name: the file name
     *  - size: file size in bytes
     *  - created: timestamp of when the file was created
     */
    private function getLogFilesMeta(): array
    {
        $dir = self::getLogDirectory();
        $files = [];
        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                $path = $dir . $file;
                if (is_file($path)) {
                    $files[] = [
                        'name'    => $file,
                        'size'    => filesize($path),
                        'created' => filectime($path),
                    ];
                }
            }
        }
        return $files;
    }

    /**
     *
     * TYPO3 log files are just plain text. Each line may contain different formats,
     * for example with a datetime + level + message, or just component/level info.
     * This method normalizes those lines into an array with keys:
     *   - datetime: (string) when the log entry was created, if available
     *   - level:    (string) log level in lowercase (error, warning, info, ...)
     *   - message:  (string) the raw log message text
     *
     * Workflow:
     * 1. Loop over each line from the log file.
     * 2. Trim whitespace; skip empty lines.
     * 3. Try to match the “standard” TYPO3 log format:
     *      Example: `Mon, 19 Aug 2024 14:52:30 ... [ERROR] Something failed`
     *    → Extract datetime, level, and the actual message.
     * 4. If not standard, try to match the “component format”:
     *      Example: `component="TYPO3.CMS.Core" ... [WARNING] More details...`
     *    → No datetime available, but we can still extract level + full line as message.
     * 5. If neither pattern fits, treat the line as a fallback:
     *      → No datetime, no level, just the raw line as message.
     * 6. Collect all parsed entries in an array and return it.
     *
     */
    private function parseLogContent(array $lines): array
    {
        $entries = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            // Match standard format "datetime [LEVEL] message"
            if (preg_match('/^(?<datetime>\w{3}, \d{2} \w{3} \d{4} \d{2}:\d{2}:\d{2}) .*? \[(?<level>[A-Z]+)\] (?<message>.*)$/', $line, $matches)) {
                $entries[] = [
                    'datetime' => $matches['datetime'],
                    'level' => strtolower($matches['level']),
                    'message' => $matches['message']
                ];
                // Match "component=..." format, no datetime available
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

    private static function getLogDirectory(): string
    {
        return Environment::getVarPath() . '/log/';
    }
}
