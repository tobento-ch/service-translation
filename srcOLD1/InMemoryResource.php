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
 * InMemoryResource
 */
class InMemoryResource implements ResourceInterface
{
    /**
     * Create a new FileLoader.
     *
     * @param string $resource The resource name
     * @param array $translations
     */    
    public function __construct(
        protected string $resource,
        protected array $translations,
    ) {}

    /**
     * Returns the resource name if any.
     *
     * @return null|string
     */    
    public function name(): null|string
    {
        return $this->resource;
    }
    
    /**
     * Returns the translations for the specified resource and locale.
     *
     * @param string $resource The resource name
     * @param string $locale The locale such as 'en-Us'. 
     * @return array The messages loaded.
     */    
    public function translations(string $resource, string $locale): array
    {
        if ($resource !== $this->name()) {
            return [];
        }
        
        return $this->translations[$locale] ?? [];
    }
}