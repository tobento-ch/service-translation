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

use Psr\Log\LoggerInterface;

/**
 * MissingTranslationHandler
 */
class MissingTranslationHandler implements MissingTranslationHandlerInterface
{
    /**
     * Create a new MissingTranslationHandler.
     *
     * @param null|LoggerInterface
     */    
    public function __construct(
        protected null|LoggerInterface $logger = null
    ) {}
    
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
    ): string {
        
        // Message might be a real message (not a keyword) and the default locale is used,
        // so it would not be a missing message.
        // Handle it here, depending on the message set on your views and such.
        
        if ($this->logger)
        {
            $this->logger->warning(
                'Missing translation key.',
                [
                    'key' => $key,
                    'parameters' => $parameters,
                    'locale' => $locale,
                    'requestedLocale' => $requestedLocale,
                ]
            );
        }
            
        return $key;
    }

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
    ): string {
    
        if ($this->logger)
        {
            $this->logger->warning(
                'Missing translation key fallbacked to locale defined.',
                [
                    'message' => $message,
                    'key' => $key,
                    'parameters' => $parameters,
                    'fallbackLocale' => $fallbackLocale,
                    'requestedLocale' => $requestedLocale,
                ]
            );
        }
            
        return $message;
    }
    
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
    ): string {
    
        if ($this->logger)
        {
            $this->logger->warning(
                'Missing translation key fallbacked to default locale.',
                [
                    'message' => $message,
                    'key' => $key,
                    'parameters' => $parameters,
                    'defaultLocale' => $defaultLocale,
                    'requestedLocale' => $requestedLocale,
                ]
            );
        }
            
        return $message;
    }        
}