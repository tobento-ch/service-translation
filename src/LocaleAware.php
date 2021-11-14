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
 * LocaleAware
 */
interface LocaleAware
{
    /**
     * Set the locale.
     *
     * @param string $locale
     * @return static $this
     */    
    public function setLocale(string $locale): static;
    
    /**
     * Returns the locale.
     *
     * @return string
     */    
    public function getLocale(): string;    

    /**
     * Set the locale fallbacks. ['de-CH' => 'en-US']
     *
     * @param array<string, string> $localeFallbacks
     * @return static $this
     */    
    public function setLocaleFallbacks(array $localeFallbacks): static;
    
    /**
     * Returns the locale fallbacks.
     *
     * @return array<string, string>
     */    
    public function getLocaleFallbacks(): array;
    
    /**
     * Set the locale mapping. ['de' (requested) => '1' (stored)]
     *
     * @param array $localeMapping
     * @return static $this
     */    
    public function setLocaleMapping(array $localeMapping): static;
    
    /**
     * Returns the locale mapping.
     *
     * @return array
     */    
    public function getLocaleMapping(): array;    
}