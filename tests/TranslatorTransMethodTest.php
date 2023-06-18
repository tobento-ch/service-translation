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

namespace Tobento\Service\Translation\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\TranslatorInterface;
use Tobento\Service\Translation\LocaleAware;
use Tobento\Service\Translation\ResourcesAware;
use Tobento\Service\Translation\ModifiersAware;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\Modifier\ParameterReplacer;
use Tobento\Service\Translation\MissingTranslationHandler;

/**
 * TranslatorTransMethodTest tests
 */
class TranslatorTransMethodTest extends TestCase
{   
    public function testUsesDefaultLocaleIfNoneAndReturnsOrgMessageIfNoResource()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translated = $translator->trans(
            message: 'Hello World',
        );
        
        $this->assertSame(
            'Hello World',
            $translated
        );
    }
    
    public function testUsesDefaultLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'en', [
                    'Hello World' => 'Hallo Welt EN',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translated = $translator->trans(
            message: 'Hello World EN',
        );
        
        $this->assertSame(
            'Hello World EN',
            $translated
        );
    }    
    
    public function testWithSpecificLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'de', [
                    'Hello World' => 'Hallo Welt',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translated = $translator->trans(
            message: 'Hello World',
            parameters: [],
            locale: 'de'
        );
        
        $this->assertSame(
            'Hallo Welt',
            $translated
        );
    }
    
    public function testWithSpecificMissingLocaleFallsbackToDefault()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'de', [
                    'Hello World' => 'Hallo Welt',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translated = $translator->trans(
            message: 'Hello World',
            parameters: [],
            locale: 'fr'
        );
        
        $this->assertSame(
            'Hello World',
            $translated
        );
    }
    
    public function testWithSpecificMissingLocaleUsesFallbackLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'de', [
                    'Hello World' => 'Hallo Welt',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['fr' => 'de'],
        );

        $translated = $translator->trans(
            message: 'Hello World',
            parameters: [],
            locale: 'fr'
        );
        
        $this->assertSame(
            'Hallo Welt',
            $translated
        );
    }
    
    public function testWithSpecificMissingLocaleUsesFallbackLocaleWithMapping()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'de-CH', [
                    'Hello World' => 'Hallo Welt',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['fr' => 'de'],
            localeMapping: ['de' => 'de-CH'],
        );

        $translated = $translator->trans(
            message: 'Hello World',
            parameters: [],
            locale: 'fr'
        );
        
        $this->assertSame(
            'Hallo Welt',
            $translated
        );
    }
    
    public function testUsesFallbackLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'de', [
                    'Hello World' => 'Hallo Welt',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['fr' => 'de'],
        );

        $translated = $translator->trans(
            message: 'Hello World',
            parameters: [],
            locale: 'fr'
        );
        
        $this->assertSame(
            'Hallo Welt',
            $translated
        );
    } 
    
    public function testUsesLocaleMapping()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'en', [
                    'Hello World' => 'Hallo Welt EN',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeMapping: ['en-US' => 'en'],
        );

        $translated = $translator->trans(
            message: 'Hello World',
            parameters: [],
            locale: 'en-US'
        );
        
        $this->assertSame(
            'Hallo Welt EN',
            $translated
        );
    }
    
    public function testUsesModifiers()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'en', [
                    'Hi :name' => 'Hi :name',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translated = $translator->trans(
            message: 'Hi :name',
            parameters: [':name' => 'John'],
            locale: 'en'
        );
        
        $this->assertSame(
            'Hi John',
            $translated
        );
    }
    
    public function testUsesModifiersOnFallbackLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'de', [
                    'Hi :name' => 'Hallo :name',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['fr' => 'de'],
        );

        $translated = $translator->trans(
            message: 'Hi :name',
            parameters: [':name' => 'John'],
            locale: 'fr'
        );
        
        $this->assertSame(
            'Hallo John',
            $translated
        );
    }
    
    public function testUsesModifiersOnFallbackToDefaultLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'de', [
                    'Hi :name' => 'Hallo :name',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translated = $translator->trans(
            message: 'Hi :name',
            parameters: [':name' => 'John'],
            locale: 'fr'
        );
        
        $this->assertSame(
            'Hi John',
            $translated
        );
    }
    
    public function testResourcesAreSortedByPriorityByDefault()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource(
                    name: '*', 
                    locale: 'en',
                    translations: ['message' => 'message 10'],
                    priority: 10,
                ),
                new Resource(
                    name: '*', 
                    locale: 'en',
                    translations: ['message' => 'message 50'],
                    priority: 50,
                ),
                new Resource(
                    name: '*', 
                    locale: 'en',
                    translations: ['message' => 'message 20'],
                    priority: 20,
                ),
            ),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $this->assertSame(
            'message 50',
            $translator->trans(message: 'message')
        );
    }
}