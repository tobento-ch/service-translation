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
 * Translators
 */
class Translators //extends Translator implements TranslatorsInterface, TranslatorInterface
{
    /**
     * @var string
     */
    protected string $locale = 'en';
    
    /**
     * @var array<string, string>
     */
    protected array $localeFallbacks = [];
    
    /**
     * @var array<string, TranslatorInterface>
     */
    protected array $translators = [];
    
    /**
     * @var null|ModifiersInterface Message modifiers.
     */
    protected null|ModifiersInterface $modifiers = null;
    
    /**
     * @var null|MissingTranslationHandlerInterface
     */
    protected null|MMissingTranslationHandlerInterface $missingTranslationHandler = null;
                
    /**
     * Create a new Translator.
     *
     * @param TranslatorInterface ...$translators
     */    
    public function __construct(
        protected TranslatorInterface ...$translators,
    ) {        
        foreach($this->translators as $translator)
        {
            $this->add($translator);
        }
    }

    /**
     * Add a translator.
     *
     * @param TranslatorInterface $translator
     * @return static $this
     */    
    public function add(TranslatorInterface $translator): static
    {
        //$translator->setMissingTranslationHandler(null);
        //$translator->setLocale($this->locale);
        //$translator->setLocaleFallbacks($this->localeFallbacks);
        
        // modifiers should not be set? or only on setModifiers() and Modifiable|ModifiersAware
        // or do not ModifiersAware here or just add()
        /*if ($this->modifiers()) {
            $translator->setModifiers($this->modifiers());
            $translator = $translator->withModifiers($this->modifiers());
        }*/
        
        $this->translators[$translator->name()] = $translator;
        return $this;
    }
    
    /**
     * Remove a translator by its name.
     *
     * @param TranslatorInterface $translator
     * @return static $this
     */    
    public function remove(string $name): static
    {
        unset($this->translators[$name]);
        return $this;
    }
    
    /**
     * Returns a new instance with the translators sorted.
     *
     * @param null|callable $callback If null, sorts by priority, highest first.
     * @return static
     */    
    public function sort(null|callable $callback = null): static
    {
        if (is_null($callback))
        {
            $callback = fn(TranslatorInterface $a, TranslatorInterface $b): int
                => $b->priority() <=> $a->priority();
        }
        
        $new = clone $this;
        uasort($new->translators, $callback);
        return $new;
    }    
    
    /**
     * Returns all translators.
     *
     * @return array<string, TranslatorInterface>
     */    
    public function all(): array
    {
        return $this->translators;
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
        // Check if translation already exists.
        $translated = $this->translateMessage($message, $parameters, $locale);

        if (!is_null($translated))
        {
            return $translated;
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
        
        $translated = $this->modifyingMessage($translated, $parameters);
        
        return $this->missingTranslationHandler->missing($translated, $parameters, $locale, $localeOrg);        
    }
    
    /**
     * Returns the translated message from the translators.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param null|string $locale The locale such as de-CH.
     * @return null|string The translated message or null.
     */
    protected function translateMessage(
        string $message,
        array $parameters = [],
        null|string $locale = null
    ): null|string {
        
        foreach($this->translators as $translator)
        {
            if ($translator->canTrans($message, $parameters, $locale))
            {
                return $translator->trans($message, $parameters, $locale);
            }
        }
        
        return null;
    }
}