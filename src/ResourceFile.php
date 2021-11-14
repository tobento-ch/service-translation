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
     * @var null|array<string, string>
     */
    protected null|array $trans = null;
    
    /**
     * Create a new ResourceFile.
     *
     * @param string|File $file
     * @param string $locale
     * @param null|string $resourceName
     * @param string $group
     * @param int $priority
     */    
    public function __construct(
        protected string|File $file,
        protected string $locale,
        protected null|string $resourceName = null,
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
        return $this->resourceName ?: $this->file()->getFilename();
    }

    /**
     * Returns the file.
     *
     * @return File
     */    
    public function file(): File
    {
        if (is_string($this->file)) {
            $this->file = new File($this->file);
        }
        
        return $this->file;
    }
    
    /**
     * Returns the resource translations.
     *
     * @return array<string, string>
     */    
    public function translations(): array
    {
        if ($this->trans) {
            return $this->trans;
        }
        
        if ($this->file()->isExtension(['php', 'x-php'])) {
            $this->trans = $this->loadPhpFile($this->file());
        }

        if ($this->file()->isExtension(['json'])) {
            $this->trans = (new JsonFile($this->file()->getFile()))->toArray();
        }
        
        return $this->trans ?: [];
    }
    
    /**
     * Load the translations from the php file
     *
     * @param File $file
     * @return array
     */
    protected function loadPhpFile(File $file): array
    {
        if (! $file->isFile()) {
            return [];
        }
            
        $translations = require $file->getFile();
        
        return is_array($translations) ? $translations : [];
    }    
}