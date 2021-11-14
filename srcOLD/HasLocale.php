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
 * HasLocale
 */
trait HasLocale
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
     * Returns the locale.
     *
     * @return string
     */    
    public function getLocale(): string
    {
        return $this->locale;
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
    
    /**
     * Returns the locale fallbacks.
     *
     * @return array
     */    
    public function getLocaleFallbacks(): array
    {
        return $this->localeFallbacks;
    }

    /**
     * Returns the locale fallback for the specified locale or null if none.
     *
     * @param string $locale
     * @return null|string
     */    
    protected function getLocaleFallback(string $locale): null|string
    {
        return $this->localeFallbacks[$locale] ?? null;
    }    
}