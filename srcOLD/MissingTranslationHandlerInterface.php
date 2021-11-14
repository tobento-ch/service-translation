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

namespace Tobento\Service\Translation;

/**
 * MissingTranslationHandlerInterface
 */
interface MissingTranslationHandlerInterface
{
    /**
     * Handle missing translation.
     *
     * @param string The key to get the translation or any text.
     * @param array Any parameters for the message.
     * @param string The locale such as de-CH.
     * @param string The requested locale such as de-CH.
     * @return string The key.
     */
    public function missing(
        string $key,
        array $parameters,
        string $locale,
        string $requestedLocale
    ): string;

    /**
     * Handle translation which uses its fallback locale.
     *
     * @param string The translated message.
     * @param string The key to get the translation or any text.
     * @param array Any parameters for the message.
     * @param string The fallback locale used for translation such as de-CH.
     * @param string The requested locale such as de-CH.
     * @return string The translated message.
     */
    public function fallback(
        string $message,
        string $key,
        array $parameters,
        string $fallbackLocale,
        string $requestedLocale
    ): string;
    
    /**
     * Handle translation which fallbacked to default locale.
     *
     * @param string The translated message.
     * @param string The key to get the translation or any text.
     * @param array Any parameters for the message.
     * @param string The default locale used for translation such as de-CH.
     * @param string The requested locale such as de-CH.
     * @return string The translated message.
     */
    public function fallbackToDefault(
        string $message,
        string $key,
        array $parameters,
        string $defaultLocale,
        string $requestedLocale
    ): string;
}