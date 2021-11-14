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
    LocaleAware,
    ResourcesAware,
    ModifiersAware
{
    use HasLocale;
    use HasResources;
    use HasModifiers;

    /**
     * @var array Caches translations
     */
    protected array $trans = [];
    
    /**
     * Create a new Translator.
     *
     * @param ResourcesInterface $resources
     * @param ModifiersInterface $modifiers
     * @param MissingTranslationHandlerInterface $missingTranslationHandler
     * @param string $locale The default locale such as en-Us
     * @param array $localeFallbacks
     * @param array $localeMapping
     * @param string $delimiter The delimiter for the keyed messages (resource.message).
     */    
    public function __construct(
        protected ResourcesInterface $resources,
        protected ModifiersInterface $modifiers,
        protected MissingTranslationHandlerInterface $missingTranslationHandler,
        string $locale = 'en',
        array $localeFallbacks = [],
        array $localeMapping = [],
        protected string $delimiter = '.'
    ) {
        $this->setLocale($locale);
        $this->setLocaleFallbacks($localeFallbacks);
        $this->setLocaleMapping($localeMapping);
    }
        
    /**
     * Returns the translated message.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param null|string $locale The locale or null to use the default.
     * @return string The translated message.
     */
    public function trans(string $message, array $parameters = [], null|string $locale = null): string
    {
        // use default locale if none.
        $locale = $locale ?: $this->getLocale();

        // check locale mapping.
        $locale = $this->localeMapping[$locale] ?? $locale;
        
        // check if translation already exists.
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
            $locale = $this->localeMapping[$locale] ?? $locale;
            
            $translated = $this->getTranslation($message, $parameters, $locale);
            
            if (!is_null($translated))
            {
                [$translated] = $this->modifiers()->modify($translated, $parameters);
                
                return $this->missingTranslationHandler->fallback($translated, $message, $parameters, $locale, $localeOrg);
            }
        }        

        // Fallback to default locale.
        if ($locale !== $this->locale)
        {
            $translated = $this->getTranslation($message, $parameters, $this->locale);
            
            if (!is_null($translated))
            {
                [$translated] = $this->modifiers()->modify($translated, $parameters);
                
                return $this->missingTranslationHandler->fallbackToDefault(
                    $translated,
                    $message,
                    $parameters,
                    $this->locale,
                    $localeOrg
                );
            }  
        }
        
        $translated = $this->modifiers()->modify($message, $parameters)[0];
        
        return $this->missingTranslationHandler->missing($translated, $message, $parameters, $locale, $localeOrg); 
    }
    
    /**
     * Returns the translations of the specified resource.
     *
     * @param string $name The resource name.
     * @param null|string $locale The locale or null to use the default.
     * @return array<string, string> The resource translations.
     */
    public function getResource(string $name, null|string $locale = null): array
    {
        $locale = $locale ?: $this->getLocale();
        $locale = $this->localeMapping[$locale] ?? $locale;
        
        if (!empty($translations = $this->fetchTranslations($name, $locale)))
        {
            return $translations;
        }

        // If resource does not exist for the given locale, use the locale fallback if any.
        if ($this->getLocaleFallback($locale))
        {
            $locale = $this->getLocaleFallback($locale);        
            $locale = $this->localeMapping[$locale] ?? $locale;
            
            if (!empty($translations = $this->fetchTranslations($name, $locale)))
            {
                return $translations;
            }
        }        

        // Fallback to default locale.
        if ($locale !== $this->locale)
        {            
            if (!empty($translations = $this->fetchTranslations($name, $this->locale)))
            {
                return $translations;
            }        
        } 
        
        return [];
    }
    
    /**
     * Returns the tanslations from specified resource and locale.
     *
     * @param string $resource The resource name
     * @param string $locale The locale such as de-CH.
     * @return array<string, string>
     */
    protected function fetchTranslations(string $resource, string $locale): array
    {        
        // Loading resource once.
        if (isset($this->trans[$resource][$locale])) {
            return $this->trans[$resource][$locale];
        }

        return $this->trans[$resource][$locale] = $this->resources->locale($locale)->name($resource)->translations();
    }    
            
    /**
     * Returns the translated message or null if none.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param string $locale The locale such as de-CH.
     * @return null|string The translated message or null if none.
     */
    protected function getTranslation(string $message, array $parameters, string $locale): null|string
    {
        // If a src is defined, we strictly look for a message translation
        // in the resource.
        if (isset($parameters['src'])) {
            return $this->fetchTranslations($parameters['src'], $locale)[$message] ?? null;
        }
        
        // Next, load default messages files.                
        $translation = $this->fetchTranslations('*', $locale)[$message] ?? null;
        
        // If translation for the message does not exist,
        // we load it from a specific resource if set.
        if (is_null($translation))
        {
            [$resource, $message] = $this->parseMessage($message);
            
            if ($resource !== null)
            {
                $translation = $this->fetchTranslations($resource, $locale)[$message] ?? null;
            }
        }
        
        return $translation;
    }
    
    /**
     * Parses and returns the parsed message.
     *
     * @param string $message
     * @return array The message parsed.
     */
    protected function parseMessage(string $message): array
    {
        $segments = explode($this->delimiter, $message);
        
        $resource = $segments[0];
        
        if (str_contains($resource, ' ')) {
            return [null, null];
        }
        
        return [$resource, $segments[1] ?? null];
    }           
}