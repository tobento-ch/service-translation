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
use Tobento\Service\Translation\MissingHandler\NullHandler;
use Tobento\Service\Translation\MissingTranslationHandlerInterface;
use Psr\Log\LoggerInterface;

class NullHandlerTest extends TestCase
{
    public function testThatImplementsInterface()
    {
        $handler = new NullHandler();

        $this->assertInstanceOf(
            MissingTranslationHandlerInterface::class,
            $handler
        );
    }

    public function testMissingReturnsTranslationUnchanged()
    {
        $handler = new NullHandler();

        $translation = 'foo';
        $result = $handler->missing(
            translation: $translation,
            message: 'bar',
            parameters: ['x' => 'y'],
            locale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame($translation, $result);
    }

    public function testFallbackReturnsTranslationUnchanged()
    {
        $handler = new NullHandler();

        $translation = 'foo';
        $result = $handler->fallback(
            translation: $translation,
            message: 'bar',
            parameters: ['x' => 'y'],
            fallbackLocale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame($translation, $result);
    }

    public function testFallbackToDefaultReturnsTranslationUnchanged()
    {
        $handler = new NullHandler();

        $translation = 'foo';
        $result = $handler->fallbackToDefault(
            translation: $translation,
            message: 'bar',
            parameters: ['x' => 'y'],
            defaultLocale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame($translation, $result);
    }

    public function testLoggerIsAlwaysNull()
    {
        $handler = new NullHandler();

        // Using reflection to ensure logger is null in parent
        $ref = new \ReflectionClass($handler);
        $prop = $ref->getParentClass()->getProperty('logger');
        $prop->setAccessible(true);

        $this->assertNull($prop->getValue($handler));
    }
}