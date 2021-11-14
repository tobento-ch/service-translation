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

/**
 * ResourceFactory
 */
class ResourceFactory implements ResourceFactoryInterface
{    
    /**
     * Create a new Resource.
     *
     * @param string $name
     * @param string $locale
     * @param array<string, string> $translations
     * @param string $group
     * @param int $priority
     * @return ResourceInterface
     */    
    public function createResource(
        string $name,
        string $locale,
        array $translations,
        string $group = 'default',
        int $priority = 0,
    ): ResourceInterface {
        return new Resource($name, $locale, $translations, $group, $priority);
    }
    
    /**
     * Create a new Resource from file.
     *
     * @param string|File $file
     * @param string $locale
     * @param string $group
     * @param int $priority
     * @return ResourceInterface
     */    
    public function createResourceFromFile(
        string|File $file,
        string $locale,
        string $group = 'default',
        int $priority = 0,
    ): ResourceInterface {
        if (is_string($file)) {
            $file = new File($file);
        }
        
        $resourceName = null;
                    
        if (str_starts_with($file->getFilename(), $locale)) {
            $resourceName = '*';
        }
        
        return new ResourceFile($file, $locale, $resourceName, $group, $priority);
    }
}