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

namespace Tobento\Service\Translation\MissingHandler;

use Tobento\Service\Translation\MissingTranslationHandlerInterface;

/**
 * Delegates missing‑translation handling to multiple handlers.
 *
 * Each handler is executed in the order it was provided. The translation
 * returned by one handler is passed to the next, allowing handlers to
 * combine behaviors such as logging, auto‑translation, storing missing
 * keys, or doing nothing.
 */
class Chain implements MissingTranslationHandlerInterface
{
    /**
     * @var array<array-key, MissingTranslationHandlerInterface>
     */
    protected array $handlers = [];
    
    public function __construct(
        MissingTranslationHandlerInterface ...$handlers
    ) {
        $this->handlers = $handlers;
    }
    
    /**
     * Handle missing translation.
     *
     * @param string $translation The translated message.
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param string $locale The locale such as de-CH.
     * @param string $requestedLocale The requested locale such as de-CH.
     * @return string The translated message.
     */
    public function missing(
        string $translation,
        string $message,
        array $parameters,
        string $locale,
        string $requestedLocale
    ): string {
        foreach ($this->handlers as $handler) {
            $translation = $handler->missing($translation, $message, $parameters, $locale, $requestedLocale);
        }
        return $translation;
    }

    /**
     * Handle translation which uses its fallback locale.
     *
     * @param string $translation The translated message.
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param string $fallbackLocale The fallback locale used for translation such as de-CH.
     * @param string $requestedLocale The requested locale such as de-CH.
     * @return string The translated message.
     */
    public function fallback(
        string $translation,
        string $message,
        array $parameters,
        string $fallbackLocale,
        string $requestedLocale
    ): string {
        foreach ($this->handlers as $handler) {
            $translation = $handler->fallback($translation, $message, $parameters, $fallbackLocale, $requestedLocale);
        }
        return $translation;
    }
    
    /**
     * Handle translation which fallbacked to default locale.
     *
     * @param string $translation The translated message.
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param string $defaultLocale The default locale used for translation such as de-CH.
     * @param string $requestedLocale The requested locale such as de-CH.
     * @return string The translated message.
     */
    public function fallbackToDefault(
        string $translation,
        string $message,
        array $parameters,
        string $defaultLocale,
        string $requestedLocale
    ): string {
        foreach ($this->handlers as $handler) {
            $translation = $handler->fallbackToDefault($translation, $message, $parameters, $defaultLocale, $requestedLocale);
        }
        return $translation;
    }
}