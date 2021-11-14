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
 * Translator
 */
abstract class Translator implements TranslatorInterface
{
    /**
     * @var string
     */
    protected string $locale = 'en';
    
    /**
     * @var array<string, string>
     */
    protected array $localeFallbacks = [];
    
    /**
     * @var null|ModifiersInterface Message modifiers.
     */
    protected null|ModifiersInterface $modifiers = null;
    
    /**
     * @var null|MissingTranslationHandlerInterface
     */
    protected null|MissingTranslationHandlerInterface $missingTranslationHandler = null;
     
    /**
     * Returns the translated message.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param null|string $locale The locale such as de-CH.
     * @return string The translated message.
     */
    abstract public function trans(string $message, array $parameters = [], null|string $locale = null): string;

    /**
     * Returns the translator name.
     *
     * @return string
     */    
    public function name(): string
    {
        return $this::class;
    }
    
    /**
     * Set the locale.
     *
     * @param string $locale
     * @return static $this
     */    
    public function locale(string $locale): static
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Set the locale fallbacks. ['de-CH' => 'en-US']
     *
     * @param array<string, string> $localeFallbacks
     * @return static $this
     */    
    public function localeFallbacks(array $localeFallbacks): static
    {
        $this->localeFallbacks = $localeFallbacks;
        return $this;
    }
}