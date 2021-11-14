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
use Tobento\Service\Filesystem\File;
use Tobento\Service\Filesystem\JsonFile;

/**
 * FileLoader
 */
class FileLoader //implements LoaderInterface
{
    /**
     * @var array<string, LoaderInterface>
     */
    protected array $loaders = [];
    
    /**
     * @var array
     */
    protected array $resources = [];    
    
    /**
     * Create a new FileLoader.
     *
     * @param DirsInterface $dirs
     */    
    public function __construct(
        protected DirsInterface $dirs,
        //LoaderInterface ...$loader,
    ) {}
    
    /**
     * Returns the translations for the specified resource and locale.
     *
     * @param string $resource The resource name
     * @param string $locale The locale such as 'en-Us'. 
     * @return array The messages loaded.
     */    
    public function load(string $resource, string $locale): array
    {
        $this->loadResources($locale);
        
        return $this->loadResource($resource, $locale);
    }
    
    /**
     * Returns the dirs.
     *
     * @return DirsInterface
     */
    public function dirs(): DirsInterface
    {        
        return $this->dirs;
    }    
    
    /**
     * Creates the resources for the specified locales.
     *
     * @param string $locale
     * @return array
     */
    protected function loadResources(string $locale): void
    {
        if (isset($this->resources[$locale])) {
            return;
        }
        
        $dir = new Dir();

        foreach($this->dirs->all() as $directory)
        {
            $files = $dir->getFiles($directory.$locale.'/', '', ['php', 'json']);

            foreach($files as $file)
            {                    
                if (! str_starts_with($file->getFilename(), $locale)) {
                    $this->resources[$locale][$file->getFilename()][] = $file;
                } else {
                    $this->resources[$locale]['*'][] = $file;
                }
            }
        }
    }    

    /**
     * Returns the loaded resource translations.
     *
     * @param string $resource The resource name
     * @param string $locale The locale such as 'en-Us'. 
     * @return array<string, string> The translations. ['message' => 'translation']
     */    
    protected function loadResource(string $resource, string $locale): array
    {
        if (!isset($this->resources[$locale][$resource])) {
            return [];
        }
        
        $translations = [];
        
        foreach($this->resources[$locale][$resource] as $file)
        {
            if ($file->isExtension(['php', 'x-php'])) {
                $translations = array_merge($translations, $this->loadPhpFile($file->getFile()));
            }

            if ($file->isExtension(['json'])) {
                $translations = array_merge($translations, (new JsonFile($file->getFile()))->toArray());
            }
        }
        
        return $translations;
    }
    
    /**
     * Load the messages from the php file
     *
     * @param string The file
     * @return array The messages on success, otherwise null.
     */
    protected function loadPhpFile(string $file): array
    {
        if (!file_exists($file)) {
            return [];
        }
            
        $messages = require $file;
        
        return is_array($messages) ? $messages : [];
    }   
}