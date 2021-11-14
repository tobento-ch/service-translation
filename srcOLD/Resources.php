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
 * Resources
 */
class Resources implements ResourcesInterface
{
    /**
     * @var array<int, ResourceInterface>
     */
    protected array $resources = [];
    
    /**
     * @var array<string, string> Caches translations ['message' => 'translation']
     */
    protected null|array $translations = null;    
    
    /**
     * Create a new Resources.
     *
     * @param ResourceFactoryInterface $resourceFactory
     */    
    public function __construct(
        //protected ResourceFactoryInterface $resourceFactory,
    ) {}

    /**
     * Adds a resource.
     *
     * @param ResourceInterface $resource
     * @return static $this
     */    
    public function add(ResourceInterface $resource): static
    {
        //$this->resourceNames[$resource->locale()][$resource->name()] = $resource;
        return $this;
    }
    
    /**
     * Returns a resource by name.
     *
     * @param string $name
     * @param string $locale
     * @return ResourceInterface
     */    
    public function get(string $name, string $locale): ResourceInterface
    {
        // filter or
        //return $this->resourceNames[$name][$locale] ?? $this->resourceFactory->createResource($name, $locale);
        
        // does not work as name could be same from another locale
        //return $this->resources[$name] ?? $this->resourceFactory->createResource($name, $locale);
        //return $this->resources[$name][$locale] ?? $this->resourceFactory->createResource($name, $locale);
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
        $new->translations = null;
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
        return $this->filter(fn(ResourceInterface $src): bool => $src->locale() === $locale);
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
     * Returns all translations.
     *
     * @return array<string, string> ['message' => 'translation']
     */    
    public function translations(): array
    {
        if ($this->translations) {
            return $this->translations;
        }
        
        $this->translations = [];
                
        foreach($this->all() as $resource)
        {
            $this->translations = array_merge($this->translations, $resource->translations());
        }
        
        return $this->translations;
    }    
}