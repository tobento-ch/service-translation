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

use Tobento\Service\Dir\DirsInterface;
use Tobento\Service\Filesystem\Dir;

/**
 * Resources
 */
class Resources implements ResourcesInterface
{
    /**
     * @var array<int, ResourceInterface>
     */
    protected array $resources = [];
    
    /**
     * @var array<int, ResourcesInterface>
     */
    protected array $sources = [];    
    
    /**
     * @var array<int, string>
     */
    protected array $loadedLocales = [];
    
    /**
     * Create a new Resources.
     *
     * @param ResourceInterface|ResourcesInterface $resources
     */    
    public function __construct(
        ResourceInterface|ResourcesInterface ...$resources,
    ) {
        foreach($resources as $resource)
        {
            $this->add($resource);
        }
    }

    /**
     * Adds a resource or resources.
     *
     * @param ResourceInterface|ResourcesInterface $resource
     * @return static $this
     */    
    public function add(ResourceInterface|ResourcesInterface $resource): static
    {
        if ($resource instanceof ResourcesInterface) {
            $this->sources[] = $resource;
        } else {
            $this->resources[] = $resource;
        }

        return $this;
    }
    
    /**
     * Returns a new instance with the resources filtered.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static
    {
        $new = clone $this;
        $new->resources = array_filter($this->resources, $callback);
        return $new;
    }
    
    /**
     * Returns a new instance with the specified locale.
     *
     * @param string $locale
     * @return static
     */    
    public function locale(string $locale): static
    {
        $this->createResources([$locale]);
        
        return $this->filter(fn(ResourceInterface $src): bool => $src->locale() === $locale);
    }
    
    /**
     * Returns a new instance with the specified locales.
     *
     * @param array $locales
     * @return static
     */    
    public function locales(array $locales): static
    {
        $this->createResources($locales);
        
        return $this->filter(fn(ResourceInterface $src): bool => in_array($src->locale(), $locales));
    }
    
    /**
     * Returns a new instance with the specified name.
     *
     * @param string $name
     * @return static
     */    
    public function name(string $name): static
    {        
        return $this->filter(fn(ResourceInterface $src): bool => $src->name() === $name);
    }    
    
    /**
     * Returns a new instance with the resources sorted.
     *
     * @param null|callable $callback If null, sorts by priority, highest first.
     * @return static
     */    
    public function sort(null|callable $callback = null): static
    {
        if (is_null($callback))
        {
            $callback = fn(ResourceInterface $a, ResourceInterface $b): int
                => $a->priority() <=> $b->priority();
        }
        
        $new = clone $this;
        uasort($new->resources, $callback);
        return $new;
    }    
    
    /**
     * Returns all resources.
     *
     * @return array<int, ResourceInterface>
     */    
    public function all(): array
    {
        return $this->resources;
    }
    
    /**
     * Returns all translations from the resources.
     *
     * @return array<string, string>
     */    
    public function translations(): array
    {        
        $translations = [];
        
        foreach($this->resources as $resource)
        {            
            $translations = array_merge(
                $translations, 
                $resource->translations()
            );
        }
        
        return $translations;
    }    
    
    /**
     * Creates the resources for the specified locales.
     *
     * @param array $locales
     * @return void
     */
    protected function createResources(array $locales): void
    {        
        foreach($locales as $locale)
        {
            if (in_array($locale, $this->loadedLocales)) {
                continue;
            }
            
            $this->loadedLocales[] = $locale;
            
            foreach($this->sources as $resources)
            {
                foreach($resources->locale($locale)->all() as $resource)
                {
                    $this->add($resource);
                }
            }
        }
    }   
}