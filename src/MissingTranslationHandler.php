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
     * @param null|LoggerInterface $logger
     */    
    public function __construct(
        protected null|LoggerInterface $logger = null
    ) {}
    
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
        
        // Message might be a real message (not a keyword) and the default locale is used,
        // so it would not be a missing message.
        // Handle it here, depending on the message set on your views and such.
        
        if ($this->logger)
        {
            $this->logger->warning(
                'Missing translation for the message',
                [
                    'translation' => $translation,
                    'message' => $message,
                    'parameters' => $parameters,
                    'locale' => $locale,
                    'requestedLocale' => $requestedLocale,
                ]
            );
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
    
        if ($this->logger)
        {
            $this->logger->warning(
                'Missing translation message fallbacked to the locale defined',
                [
                    'translation' => $translation,
                    'message' => $message,
                    'parameters' => $parameters,
                    'fallbackLocale' => $fallbackLocale,
                    'requestedLocale' => $requestedLocale,
                ]
            );
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
    
        if ($this->logger)
        {
            $this->logger->warning(
                'Missing translation message fallbacked to default locale.',
                [
                    'translation' => $translation,
                    'message' => $message,
                    'parameters' => $parameters,
                    'defaultLocale' => $defaultLocale,
                    'requestedLocale' => $requestedLocale,
                ]
            );
        }
            
        return $translation;
    }        
}