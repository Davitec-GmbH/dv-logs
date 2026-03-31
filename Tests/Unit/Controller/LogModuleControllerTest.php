<?php

declare(strict_types=1);

namespace Davitec\Dvlogs\Tests\Unit\Controller;

use Davitec\Dvlogs\Controller\LogModuleController;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LogModuleControllerTest extends UnitTestCase
{
    #[Test]
    public function testParseLogContentStandardFormat(): void
    {
        $controller = $this->getAccessibleMock(
            LogModuleController::class,
            [],
            [],
            '',
            false
        );

        $lines = [
            'Mon, 19 Aug 2024 14:52:30 +0000 [ERROR] request="abc123" Something failed',
        ];

        $result = $controller->_call('parseLogContent', $lines);

        self::assertCount(1, $result);
        self::assertSame('Mon, 19 Aug 2024 14:52:30', $result[0]['datetime']);
        self::assertSame('error', $result[0]['level']);
        self::assertStringContainsString('Something failed', $result[0]['message']);
    }

    #[Test]
    public function testParseLogContentComponentFormat(): void
    {
        $controller = $this->getAccessibleMock(
            LogModuleController::class,
            [],
            [],
            '',
            false
        );

        $lines = [
            'component="TYPO3.CMS.Core" severity="ERROR" [WARNING] Some warning message',
        ];

        $result = $controller->_call('parseLogContent', $lines);

        self::assertCount(1, $result);
        self::assertSame('', $result[0]['datetime']);
        self::assertSame('warning', $result[0]['level']);
    }

    #[Test]
    public function testParseLogContentFallbackFormat(): void
    {
        $controller = $this->getAccessibleMock(
            LogModuleController::class,
            [],
            [],
            '',
            false
        );

        $lines = ['Just a plain line without any format'];

        $result = $controller->_call('parseLogContent', $lines);

        self::assertCount(1, $result);
        self::assertSame('', $result[0]['datetime']);
        self::assertSame('', $result[0]['level']);
        self::assertSame('Just a plain line without any format', $result[0]['message']);
    }

    #[Test]
    public function testParseLogContentSkipsEmptyLines(): void
    {
        $controller = $this->getAccessibleMock(
            LogModuleController::class,
            [],
            [],
            '',
            false
        );

        $lines = ['', '   ', 'Mon, 19 Aug 2024 14:52:30 +0000 [INFO] request="x" OK'];

        $result = $controller->_call('parseLogContent', $lines);

        self::assertCount(1, $result);
        self::assertSame('info', $result[0]['level']);
    }

    #[Test]
    public function testParseLogContentMultipleEntries(): void
    {
        $controller = $this->getAccessibleMock(
            LogModuleController::class,
            [],
            [],
            '',
            false
        );

        $lines = [
            'Mon, 19 Aug 2024 14:52:30 +0000 [ERROR] request="a" Error one',
            'Mon, 19 Aug 2024 14:52:31 +0000 [WARNING] request="b" Warning two',
            'Plain fallback line',
        ];

        $result = $controller->_call('parseLogContent', $lines);

        self::assertCount(3, $result);
        self::assertSame('error', $result[0]['level']);
        self::assertSame('warning', $result[1]['level']);
        self::assertSame('', $result[2]['level']);
    }

    #[Test]
    public function testGetLogDirectoryReturnsVarLogPath(): void
    {
        $controller = $this->getAccessibleMock(
            LogModuleController::class,
            [],
            [],
            '',
            false
        );

        $result = $controller->_call('getLogDirectory');

        self::assertStringEndsWith('/log/', $result);
    }
}
