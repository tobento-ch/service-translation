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
 * FilesResources
 */
class FilesResources implements ResourcesInterface
{
    /**
     * @var array
     */
    protected array $resources = [];
    
    /**
     * @var null|array<string, array<string, string>> Caches translations
     */
    protected array $translations = [];
    
    /**
     * @var array<int, string>
     */
    protected array $loadedLocales = [];    
    
    /**
     * Create a new FilesResources.
     *
     * @param DirsInterface $dirs
     */    
    public function __construct(
        protected DirsInterface $dirs,
    ) {}

    /**
     * Adds a resource.
     *
     * @param ResourceInterface $resource
     * @return static $this
     */    
    protected function add(ResourceInterface $resource): static
    {
        $this->resources[$resource->locale()][$resource->name()] = $resource;
        return $this;
    }
    
    /**
     * Returns a resource.
     *
     * @param string $name
     * @param string $locale
     * @return null|ResourceInterface
     */    
    public function get(string $name, string $locale): null|ResourceInterface
    {
        $this->createResources([$locale]);
        
        return $this->resources[$locale][$name] ?? null;
    }
    
    /**
     * Returns all resources.
     *
     * @return array<string, ResourceInterface>
     */    
    public function all(string $locale): array
    {
        $this->createResources([$locale]);
        
        return $this->resources[$locale] ?? [];
    }

    /**
     * Returns the translation for the specified message or null if none.
     *
     * @return string $message
     * @return null|string
     */    
    public function translation(string $message, string $locale): null|string
    {
        // for lazy would need parameters for the src such as shop.
        // $loadedSources[$locale][$src].
        return $this->translations($locale)[$message] ?? null;
    }
        
    /**
     * Returns all translations.
     *
     * @return array<string, string> ['message' => 'translation']
     */    
    public function translations(string $locale): array
    {
        if (isset($this->translations[$locale])) {
            return $this->translations[$locale];
        }
        
        $this->translations[$locale] = [];
        
        foreach($this->all($locale) as $resource)
        {
            //if ($resource->isLazy()) {
            // better?
            
            if (in_array($resource->name(), ['shop'])) {
                continue;
            }
            
            $this->translations[$locale] = array_merge(
                $this->translations[$locale], 
                $resource->translations()
            );
        }
        
        return $this->translations[$locale];
    }

    /**
     * Creates the resources for the specified locales.
     *
     * @param array $locales
     * @return array
     */
    protected function createResources(array $locales): void
    {
        $dir = new Dir();
        
        foreach($locales as $locale)
        {
            if (in_array($locale, $this->loadedLocales)) {
                continue;
            }
            
            $this->loadedLocales[] = $locale;
            
            foreach($this->dirs->all() as $directory)
            {
                $files = $dir->getFiles($directory.$locale.'/', '', ['php', 'json']);

                foreach($files as $file)
                {                    
                    if (! str_starts_with($file->getFilename(), $locale)) {
                        //continue;
                    }
                    
                    // this does not work as it will overwrite the source because the name is the same.
                    // solution: allow only one dir.
                    // if hasResource($file->getFilename(), $locale) // merge translations
                    // $directory->name()
                    
                    $this->add(new ResourceFile($file, $locale));
                }
            }
        }
    }    
}