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
use Tobento\Service\Translation\MissingHandler\Chain;
use Tobento\Service\Translation\MissingHandler\NullHandler;
use Tobento\Service\Translation\MissingTranslationHandlerInterface;

class ChainTest extends TestCase
{
    private function createDummyHandler(string $suffix): MissingTranslationHandlerInterface
    {
        return new class($suffix) implements MissingTranslationHandlerInterface {
            public function __construct(private string $suffix) {}

            public function missing(
                string $translation,
                string $message,
                array $parameters,
                string $locale,
                string $requestedLocale
            ): string {
                return $translation . $this->suffix;
            }

            public function fallback(
                string $translation,
                string $message,
                array $parameters,
                string $fallbackLocale,
                string $requestedLocale
            ): string {
                return $translation . $this->suffix;
            }

            public function fallbackToDefault(
                string $translation,
                string $message,
                array $parameters,
                string $defaultLocale,
                string $requestedLocale
            ): string {
                return $translation . $this->suffix;
            }
        };
    }

    public function testImplementsInterface()
    {
        $handler = new Chain(new NullHandler());

        $this->assertInstanceOf(
            MissingTranslationHandlerInterface::class,
            $handler
        );
    }

    public function testMissingDelegatesToHandlersInOrder()
    {
        $chain = new Chain(
            $this->createDummyHandler('-a'),
            $this->createDummyHandler('-b'),
            new NullHandler()
        );

        $result = $chain->missing(
            translation: 'foo',
            message: 'bar',
            parameters: ['x' => 'y'],
            locale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame('foo-a-b', $result);
    }

    public function testFallbackDelegatesToHandlersInOrder()
    {
        $chain = new Chain(
            $this->createDummyHandler('-f1'),
            $this->createDummyHandler('-f2')
        );

        $result = $chain->fallback(
            translation: 'foo',
            message: 'bar',
            parameters: ['x' => 'y'],
            fallbackLocale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame('foo-f1-f2', $result);
    }

    public function testFallbackToDefaultDelegatesToHandlersInOrder()
    {
        $chain = new Chain(
            $this->createDummyHandler('-d1'),
            $this->createDummyHandler('-d2')
        );

        $result = $chain->fallbackToDefault(
            translation: 'foo',
            message: 'bar',
            parameters: ['x' => 'y'],
            defaultLocale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame('foo-d1-d2', $result);
    }

    public function testChainWithOnlyNullHandlersReturnsTranslationUnchanged()
    {
        $chain = new Chain(new NullHandler(), new NullHandler());

        $result = $chain->missing(
            translation: 'foo',
            message: 'bar',
            parameters: [],
            locale: 'de',
            requestedLocale: 'de'
        );

        $this->assertSame('foo', $result);
    }
}