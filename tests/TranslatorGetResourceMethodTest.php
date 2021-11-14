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
 * TranslatorGetResourceMethodTest tests
 */
class TranslatorGetResourceMethodTest extends TestCase
{       
    public function testUsesDefaultLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'en', [
                    'Hello World' => 'Hello World',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translations = $translator->getResource(
            name: '*',
        );
        
        $this->assertSame(
            [
                'Hello World' => 'Hello World',
            ],
            $translations
        );
    }

    public function testWithSpecificLocale()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'en', [
                    'Hello World' => 'Hello World',
                ]),
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

        $translations = $translator->getResource(
            name: '*',
            locale: 'de',
        );
        
        $this->assertSame(
            [
                'Hello World' => 'Hallo Welt',
            ],
            $translations
        );
    }
    
    public function testWithSpecificLocaleFallsbackToDefault()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('*', 'en', [
                    'Hello World' => 'Hello World',
                ]),               
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
        );

        $translations = $translator->getResource(
            name: '*',
            locale: 'de',
        );
        
        $this->assertSame(
            [
                'Hello World' => 'Hello World',
            ],
            $translations
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

        $translations = $translator->getResource(
            name: '*',
            locale: 'fr',
        );
        
        $this->assertSame(
            [
                'Hello World' => 'Hallo Welt',
            ],
            $translations
        );
    }
    
    public function testUsesLocaleMapping()
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

        $translations = $translator->getResource(
            name: '*',
            locale: 'de',
        );
        
        $this->assertSame(
            [
                'Hello World' => 'Hallo Welt',
            ],
            $translations
        );
    }
    
    public function testUsesLocaleMappingOnFallback()
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

        $translations = $translator->getResource(
            name: '*',
            locale: 'fr',
        );
        
        $this->assertSame(
            [
                'Hello World' => 'Hallo Welt',
            ],
            $translations
        );
    }    
}