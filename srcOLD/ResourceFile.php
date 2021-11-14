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

use Tobento\Service\Filesystem\File;
use Tobento\Service\Filesystem\JsonFile;

/**
 * Resource
 */
class ResourceFile extends Resource
{
    /**
     * @var null|array<string, string> ['message' => 'translation']
     */
    protected null|array $translations = null;
    
    /**
     * Create a new Resource.
     *
     * @param 
     */    
    public function __construct(
        protected File $file,
        protected string $locale,
    ) {}
    
    /**
     * Returns the resource name.
     *
     * @return string
     */    
    public function name(): string
    {
        return $this->file->getFilename();
    }

    /**
     * Returns the file.
     *
     * @return File
     */    
    public function file(): File
    {
        return $this->file;
    }
    
    /**
     * Returns the translations.
     *
     * @return array<string, string> ['message' => 'translation']
     */    
    public function translations(): array
    {
        if ($this->translations) {
            return $this->translations;
        }
        
        if ($this->file->isExtension(['php'])) {
            $this->translations = $this->loadPhpFile($this->file);
        }

        if ($this->file->isExtension(['json'])) {
            $this->translations = (new JsonFile($this->file->getFile()))->toArray();
        }
        
        return $this->translations;
    }
    
    /**
     * Load the translations from the php file
     *
     * @param File $file
     * @return null|array
     */
    protected function loadPhpFile(File $file): null|array
    {
        if ($file->isFile()) {
            return null;
        }
            
        $translations = require $file->getFile();
        
        return is_array($translations) ? $translations : null;
    }    
}