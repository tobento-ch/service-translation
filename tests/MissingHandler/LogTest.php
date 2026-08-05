<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Translation\Test\MissingHandler;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Translation\MissingHandler\Log;
use Tobento\Service\Translation\MissingTranslationHandlerInterface;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

class LogTest extends TestCase
{
    public function testImplementsInterface()
    {
        $handler = new Log(new Logger('test'));

        $this->assertInstanceOf(
            MissingTranslationHandlerInterface::class,
            $handler
        );
    }

    public function testMissingLogsWarning()
    {
        $testHandler = new TestHandler();
        $logger = new Logger('test');
        $logger->pushHandler($testHandler);

        $handler = new Log($logger);

        $translation = 'foo';

        $result = $handler->missing(
            translation: $translation,
            message: 'bar',
            parameters: ['x' => 'y'],
            locale: 'de',
            requestedLocale: 'de'
        );

        // translation unchanged
        $this->assertSame($translation, $result);

        // one log entry
        $this->assertTrue($testHandler->hasWarningRecords());

        $records = $testHandler->getRecords();
        $record = $records[0];

        $this->assertSame('Missing translation for the message', $record['message']);
        $this->assertSame([
            'translation' => 'foo',
            'message' => 'bar',
            'parameters' => ['x' => 'y'],
            'locale' => 'de',
            'requestedLocale' => 'de',
        ], $record['context']);
    }

    public function testFallbackLogsWarning()
    {
        $testHandler = new TestHandler();
        $logger = new Logger('test');
        $logger->pushHandler($testHandler);

        $handler = new Log($logger);

        $translation = 'foo';

        $result = $handler->fallback(
            translation: $translation,
            message: 'bar',
            parameters: ['x' => 'y'],
            fallbackLocale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame($translation, $result);
        $this->assertTrue($testHandler->hasWarningRecords());

        $record = $testHandler->getRecords()[0];

        $this->assertSame(
            'Missing translation message fallbacked to the locale defined',
            $record['message']
        );

        $this->assertSame([
            'translation' => 'foo',
            'message' => 'bar',
            'parameters' => ['x' => 'y'],
            'fallbackLocale' => 'de',
            'requestedLocale' => 'de',
        ], $record['context']);
    }

    public function testFallbackToDefaultLogsWarning()
    {
        $testHandler = new TestHandler();
        $logger = new Logger('test');
        $logger->pushHandler($testHandler);

        $handler = new Log($logger);

        $translation = 'foo';

        $result = $handler->fallbackToDefault(
            translation: $translation,
            message: 'bar',
            parameters: ['x' => 'y'],
            defaultLocale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame($translation, $result);
        $this->assertTrue($testHandler->hasWarningRecords());

        $record = $testHandler->getRecords()[0];

        $this->assertSame(
            'Missing translation message fallbacked to default locale.',
            $record['message']
        );

        $this->assertSame([
            'translation' => 'foo',
            'message' => 'bar',
            'parameters' => ['x' => 'y'],
            'defaultLocale' => 'de',
            'requestedLocale' => 'de',
        ], $record['context']);
    }
}