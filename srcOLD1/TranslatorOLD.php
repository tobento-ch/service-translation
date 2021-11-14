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
 * Translator
 */
/**
 * Translator
 */
class Translator implements
    TranslatorInterface,
    ModifiersAware,
{
    use HasLocale;
    use HasModifiers;

    /**
     * @var null|array<string, array<string, string>> Caches translations
     */
    protected array $trans = [];
    
    /**
     * @var array Holds the loaded resources.
     */
    protected array $loaded = [];
                
    /**
     * Create a new Translator.
     *
     * @param LoaderInterface $loader
     * @param ModifiersInterface $modifiers
     * @param MissingTranslationHandlerInterface $missingTranslationHandler
     * @param string $locale The default locale such as en-Us
     * @param array $localeFallbacks
     * @param string The separator for the keyed messages.
     */    
    public function __construct(
        protected LoaderInterface $loader,
        protected ModifiersInterface $modifiers,
        protected MissingTranslationHandlerInterface $missingTranslationHandler,
        protected string $locale = 'en',
        protected array $localeFallbacks = [],
        protected string $separator = '.'
    ) {}
        
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
        $translated = $this->getTranslation($message, $parameters, $locale);

        if (!is_null($translated))
        {
            return $this->modifiers()->modify($translated, $parameters)[0];
        }
        
        $localeOrg = $locale;
        
        // If message does not exist for the given locale, use the locale fallback if any.
        if ($this->getLocaleFallback($locale))
        {
            $locale = $this->getLocaleFallback($locale);
            
            $translated = $this->getTranslation($message, $parameters, $locale);
            
            if (is_null($translated))
            {
                return $this->missingTranslationHandler->fallback($translated, $message, $parameters, $locale, $localeOrg);
            }
        }        

        // Fallback to default locale.
        if ($locale !== $this->locale)
        {
            $translated = $this->getTranslation($message, $parameters, $this->locale);
            
            if (is_null($translated))
            {
                return $this->missingTranslationHandler->fallbackToDefault($translated, $message, $parameters, $this->locale, $localeOrg);
            }            
        }
        
        $translated = $this->modifiers()->modify($message, $parameters)[0];
        
        return $this->missingTranslationHandler->missing($translated, $parameters, $locale, $localeOrg); 
    }
    
    /**
     * Returns the unmodified translations of a resource.
     *
     * @param string $resource The resource name.
     * @param null|string $locale The locale such as de-CH.
     * @return array<string, string> The resource messages.
     */
    public function getResource(string $resource, null|string $locale = null): array
    {
        $locale = $locale ?: $this->locale;

        if (is_array($translations = $this->trans[$resource][$locale] ?? null))
        {
            return $translations;
        }

        // If resource does not exist for the given locale, use the locale fallback if any.
        if ($this->getLocaleFallback($locale))
        {
            $locale = $this->getLocaleFallback($locale);        

            $this->load($resource, $locale);
            
            if (is_array($translations = $this->trans[$resource][$locale] ?? null))
            {
                return $translations;
            }
        }        

        // Fallback to default locale.
        if ($locale !== $this->locale)
        {
            $this->load($resource, $this->locale);
            
            if (is_array($translations = $this->trans[$resource][$this->locale] ?? null))
            {
                return $translations;
            }        
        } 
        
        return [];
    }

    /**
     * Returns the loader.
     *
     * @return LoaderInterface
     */    
    public function loader(): LoaderInterface
    {
        return $this->loader;
    }
            
    /**
     * Returns the translated message or null if none.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param string $locale The locale such as de-CH.
     * @return null|string The translated message or null if none.
     */
    protected function getTranslation(string $message, array $parameters = [], string $locale): null|string
    {
        // If a src is defined, we strictly look for a message translation
        // in the resource.
        if (isset($parameters['src'])) {
            $this->load($parameters['src'], $locale);
            return $this->trans[$parameters['src']][$locale][$message] ?? null;
        }
        
        // Next, load default messages files.
        $this->load('*', $locale);
                
        $translation = $this->trans['*'][$locale][$message] ?? null;
        
        // If translation for the message does not exist,
        // we load it from a specific resource if set.
        if (is_null($translation))
        {
            [$resource, $message] = $this->parseMessage($message);
            
            if ($resource !== null)
            {
                $this->load($resource, $locale);
                
                $translation = $this->trans[$resource][$locale][$message] ?? null;
            }
        }
        
        return $translation;
    }
    
    /**
     * Load the resource for the given locale.
     *
     * @param string $resource The resource name
     * @param string $locale The locale such as de-CH.
     * @return void
     */
    protected function load(string $resource, string $locale): void
    {        
        // Loading resource once.
        if (isset($this->loaded[$resource][$locale])) {
            return;
        }
        
        $this->loaded[$resource][$locale] = true;
        
        $translations = $this->loader->load($resource, $locale);
        
        // If a resource already exist we merge them.
        if (isset($this->trans[$resource][$locale])) {
            $this->trans[$resource][$locale] = array_merge($this->trans[$resource][$locale], $translations);
        } else {
            $this->trans[$resource][$locale] = $translations;
        }
    }
    
    /**
     * Parses the message.
     *
     * @param string The key to get the translation or any text.
     * @return array The key parsed. [$resource, $key]
     */
    protected function parseMessage(string $key): array
    {
        $segments = explode($this->separator, $key);
        
        $resource = $segments[0];
        
        if (str_contains($resource, ' ')) {
            return [null, null];
        }
        
        return [$resource, $segments[1] ?? null];
    }

    /**
     * If locale has a fallback.
     *
     * @param string The locale.
     * @return null|string The locale fallback or null if none.
     */    
    protected function getLocaleFallback(string $locale): ?string
    {
        return $this->localeFallbacks[$locale] ?? null;
    }              
}