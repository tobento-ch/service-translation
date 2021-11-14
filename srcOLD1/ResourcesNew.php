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
class ResourcesNew implements ResourcesInterface
{
    /**
     * @var array<string, ResourceInterface>
     */
    protected array $resources = [];
    
    /**
     * Create a new Resources.
     *
     * @param ResourceInterface $resources
     */    
    public function __construct(
        ResourceInterface ...$resources,
    ) {
        foreach($resources as $resource)
        {
            $this->add($resource);
        }
    }

    /**
     * Adds a resource.
     *
     * @param ResourceInterface $resource
     * @return static $this
     */    
    protected function add(ResourcesInterface|ResourceInterface $resource): static
    {
        if ($resource instanceof ResourcesInterface) {
            foreach($resource as $src) {
                
            }
        }
        
        $this->resources[] = $resource;
        return $this;
    }
    
    /**
     * Returns all resources.
     *
     * @return array<string, ResourceInterface>
     */    
    public function all(): array
    {
        return $this->resources;
    }

    /**
     * Returns the translation for the specified message or null if none.
     *
     * @param string $message
     * @param array $parameters Any parameters for the message.
     * @param string $locale
     * @return null|string
     */    
    /*public function translation(string $message, array $parameters, string $locale): null|string
    {
        if (isset($parameters['src'])) {            
            return $this->translations($parameters['src'], $locale)[$message] ?? null;
        }
        
        return $this->translations($locale)[$message] ?? null;
    }*/
        
    /**
     * Returns all translations.
     *
     * @return array<string, string> ['message' => 'translation']
     */    
    public function translations(string $name, string $locale): array
    {
        if (isset($this->trans[$name][$locale])) {
            return $this->trans[$name][$locale];
        }
        
        $translations = [];
        
        foreach($this->resources as $resource)
        {
            if (
                !is_null($resource->name())
                && $resource->name() !== $name
            ) {
                continue;
            }
            
            $translations = array_merge(
                $translations, 
                $resource->translations($name, $locale)
            );
        }
        
        return $this->trans[$name][$locale] = $translations;
    }   
}