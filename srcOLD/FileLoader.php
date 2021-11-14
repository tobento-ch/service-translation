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
class FileLoader
{    
    /**
     * @var array Caches the translated unmodified messages.
     */
    protected array $translated = [];
    
    /**
     * Create a new LocalePrefixedFiles.
     *
     * @param DirsInterface $dirs
     */    
    public function __construct(
        protected DirsInterface $dirs,
        //protected PhpFileLoader
        //protected string '{dir}/{locale}/{file}', // migration?
        //protected MissingTranslationHandlerInterface $missingTranslationHandler,
        string $locale = 'en',
        null|ModifiersInterface $modifiers = null,
    ) {
        $this->modifiers($modifiers ?: new Modifiers());
        $this->locale($locale);
    }
        
    /**
     * Returns true if the message can be translated, otherwise false.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param null|string $locale The locale such as de-CH.
     * @return bool
     */
    public function canTrans(string $message, array $parameters = [], null|string $locale = null): bool
    {
        $locale = $locale ?: $this->getLocale();

        $translated = $this->translateMessage($message, $parameters, $locale);
        
        return is_null($translated) ? false : true;
    }

    /**
     * Returns the translated message.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param null|string $locale The locale such as de-CH.
     * @return string The translated message.
     */
    public function trans(string $message, array $parameters = [], null|string $locale = null): string
    {
        $locale = $locale ?: $this->getLocale();
        
        // Check if translation already exists.
        $translated = $this->translateMessage($message, $parameters, $locale);

        if (!is_null($translated))
        {
            return $this->getModifiers()->modify($translated, $parameters)[0];
        }
        
        $localeOrg = $locale;
        
        // If message does not exist for the given locale, use the locale fallback if any.
        if ($this->getLocaleFallback($locale))
        {
            $locale = $this->getLocaleFallback($locale);
            
            $translated = $this->translateMessage($translated, $parameters, $locale);
            
            if (is_null($translated))
            {
                return $this->missingTranslationHandler->fallback($translated, $message, $parameters, $locale, $localeOrg);
            }
        }        

        // Fallback to default locale.
        if ($locale !== $this->locale)
        {
            $translated = $this->translateMessage($message, $parameters, $this->locale);
            
            if (is_null($translated))
            {
                return $this->missingTranslationHandler->fallbackToDefault($translated, $message, $parameters, $this->locale, $localeOrg);
            }            
        }
        
        $translated = $this->getModifiers()->modify($translated, $parameters)[0];
        
        return $this->missingTranslationHandler->missing($translated, $parameters, $locale, $localeOrg);        
    }
        
    /**
     * Returns the translated message or null.
     *
     * @param string $message
     * @param array $parameters
     * @param string $locale
     * @return null|string The translated message or null.
     */
    protected function translateMessage(
        string $message,
        array $parameters = [],
        string $locale = null
    ): null|string {
                
        if (!isset($this->translated[$locale])) {
            $this->translated[$locale] = $this->fetchTranslations($locale);
        }
        
        if (isset($this->translated[$locale][$message])) {
            return $this->translated[$locale][$message];
        }
        
        return null;
    }        
        
    /**
     * Fetches the translations by locale.
     *
     * @param string $locale
     * @return array
     */
    protected function fetchTranslations(string $locale): array
    {
        $dir = new Dir();

        $translations = [];
            
        foreach($this->dirs->all() as $directory) {
            
            $files = $dir->getFiles($directory.$locale.DIRECTORY_SEPARATOR, '', ['php', 'json']);
            
            foreach($files as $file) {
                
                if (! str_starts_with($file->getFilename(), $locale)) {
                    continue;
                }
                
                if ($file->isExtension(['php'])) {
                
                    $loadedTranslations = $this->loadPhpFile($file);
                    
                    if (is_array($loadedTranslations)) {
                        $translations = array_merge($translations, $loadedTranslations);
                    }
                }
                
                if ($file->isExtension(['json'])) {
                    $translations = array_merge($translations, (new JsonFile($file->getFile()))->toArray());
                }
            }
        }
        
        return $translations;
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