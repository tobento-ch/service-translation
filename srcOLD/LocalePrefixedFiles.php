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
 * LocalePrefixedFiles
 */
class LocalePrefixedFiles implements
    TranslatorInterface,
    ModifiersAware,
    ResourcesAware
{
    use HasLocale;
    use HasModifiers;
    use HasResources;
    
    /**
     * Create a new LocalePrefixedFiles.
     *
     * @param DirsInterface $dirs
     */    
    public function __construct(
        protected string $name,
        protected DirsInterface $dirs,
        //protected FileLoader
        //protected string '{dir}/{locale}/{file}', // migration?
        protected MissingTranslationHandlerInterface $missingTranslationHandler,
        string $locale = 'en',
        null|ModifiersInterface $modifiers = null,
    ) {
        $this->locale($locale);
        $this->setModifiers($modifiers ?: new Modifiers());    
        $this->setResources(new FilesResources($dirs));
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

        $translated = $this->resources()->translation($message, $locale);
        
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
        $translated = $this->resources()->translation($message, $locale);

        if (!is_null($translated))
        {
            return $this->modifiers()->modify($translated, $parameters)[0];
        }
        
        $localeOrg = $locale;
        
        // If message does not exist for the given locale, use the locale fallback if any.
        if ($this->getLocaleFallback($locale))
        {
            $locale = $this->getLocaleFallback($locale);
            
            $translated = $this->resources()->translation($message, $locale);
            
            if (is_null($translated))
            {
                return $this->missingTranslationHandler->fallback($translated, $message, $parameters, $locale, $localeOrg);
            }
        }        

        // Fallback to default locale.
        if ($locale !== $this->locale)
        {
            $translated = $this->resources()->translation($message, $this->locale);
            
            if (is_null($translated))
            {
                return $this->missingTranslationHandler->fallbackToDefault($translated, $message, $parameters, $this->locale, $localeOrg);
            }            
        }
        
        $translated = $this->modifiers()->modify($message, $parameters)[0];
        
        return $this->missingTranslationHandler->missing($translated, $parameters, $locale, $localeOrg);        
    }
}