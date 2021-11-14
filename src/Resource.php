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
     * @param string $name
     * @param string $locale
     * @param array<string, string> $translations
     * @param string $group
     * @param int $priority
     */    
    public function __construct(
        protected string $name,
        protected string $locale,
        protected array $translations,
        protected string $group = 'default',
        protected int $priority = 0,
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
     * Returns the resource locale.
     *
     * @return string
     */    
    public function locale(): string
    {
        return $this->locale;
    }

    /**
     * Returns the resource group.
     *
     * @return string
     */    
    public function group(): string
    {
        return $this->group;
    }
    
    /**
     * Returns the resource priority.
     *
     * @return int
     */    
    public function priority(): int
    {
        return $this->priority;
    }    
    
    /**
     * Returns the resource translations.
     *
     * @return array<string, string>
     */    
    public function translations(): array
    {        
        return $this->translations;
    }
}