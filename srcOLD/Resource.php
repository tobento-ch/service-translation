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
 * Resource
 */
class Resource implements ResourceInterface
{    
    /**
     * Create a new Resource.
     *
     * @param 
     */    
    public function __construct(
        protected string $name,
        protected string $locale,
    ) {}
    
    /**
     * Returns the resource name.
     *
     * @return string
     */    
    public function name(): string
    {
        return $this->name;
    }
    
    /**
     * Returns the locale.
     *
     * @return string
     */    
    public function locale(): string
    {
        return $this->locale;
    }    

    /**
     * Returns the translation for the specified message or null if none.
     *
     * @return string $message
     * @return null|string
     */    
    public function translation(string $message): null|string
    {        
        return $this->translations()[$message] ?? null;
    }
    
    /**
     * Returns the translations.
     *
     * @return array<string, string> ['message' => 'translation']
     */    
    public function translations(): array
    {        
        return [];
    }
}