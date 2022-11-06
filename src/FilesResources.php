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
class FilesResources extends Resources
{
    /**
     * @var ResourceFactoryInterface
     */
    protected ResourceFactoryInterface $resourceFactory;
    
    /**
     * Create a new FilesResources.
     *
     * @param DirsInterface $dirs
     * @param ResourceFactoryInterface $resourceFactory
     */
    public function __construct(
        protected DirsInterface $dirs,
        null|ResourceFactoryInterface $resourceFactory = null,
    ) {
        $this->resourceFactory = $resourceFactory ?: new ResourceFactory();
    }

    /**
     * Creates the resources for the specified locales.
     *
     * @param array $locales
     * @return void
     */
    protected function createResources(array $locales): void
    {        
        $dir = new Dir();
        
        foreach($locales as $locale)
        {
            if (in_array($locale, $this->loadedLocales)) {
                continue;
            }
            
            if (!$this->isValidLocale($locale)) {
                continue;
            }
            
            $this->loadedLocales[] = $locale;
            
            foreach($this->dirs->all() as $directory)
            {
                $files = $dir->getFiles($directory->dir().$locale.'/');

                foreach($files as $file)
                {
                    $this->add($this->resourceFactory->createResourceFromFile(
                        $file,
                        $locale,
                        $directory->group(),
                        $directory->priority()
                    ));
                }
            }
            
            foreach($this->sources as $resources)
            {
                foreach($resources->locale($locale)->all() as $resource)
                {
                    $this->add($resource);
                }
            }
        }
    }
    
    /**
     * Returns true if the locale is valid, otherwise null.
     *
     * @param string $locale
     * @return bool
     */    
    protected function isValidLocale(string $locale): bool
    {
        return (bool) preg_match('/^[a-zA-Z_-]{2,5}$/u', $locale);
    }
}