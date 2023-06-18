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
use Tobento\Service\Translation\ResourcesInterface;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\ModifiersInterface;
use Tobento\Service\Translation\Modifier\ParameterReplacer;
use Tobento\Service\Translation\MissingTranslationHandler;

/**
 * TranslatorTest tests
 */
class TranslatorTest extends TestCase
{    
    public function testThatImplementsTranslatorInterface()
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
            localeFallbacks: ['de' => 'en'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $this->assertInstanceOf(
            TranslatorInterface::class,
            $translator
        );     
    }

    public function testThatImplementsLocaleAware()
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
            localeFallbacks: ['de' => 'en'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $this->assertInstanceOf(
            LocaleAware::class,
            $translator
        );     
    }
    
    public function testSetAndGetLocale()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'en'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $translator->setLocale('de');
        
        $this->assertSame(
            'de',
            $translator->getLocale()
        );     
    }
    
    public function testLocaleFallbacks()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'fr'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $translator->setLocaleFallbacks(['de' => 'en']);
        
        $this->assertSame(
            ['de' => 'en'],
            $translator->getLocaleFallbacks()
        );     
    }
    
    public function testLocaleMapping()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'fr'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $translator->setLocaleMapping(['de-DE' => 'de-CH', 'de' => 'de-CH']);
        
        $this->assertSame(
            ['de-DE' => 'de-CH', 'de' => 'de-CH'],
            $translator->getLocaleMapping()
        );     
    }    
    
    public function testThatImplementsResourcesAware()
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
            localeFallbacks: ['de' => 'en'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $this->assertInstanceOf(
            ResourcesAware::class,
            $translator
        );     
    }
    
    public function testResourcesMethod()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'fr'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $this->assertInstanceOf(
            ResourcesInterface::class,
            $translator->resources()
        );     
    }
    
    public function testWithResourcesMethod()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'fr'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $resources = new Resources();
        
        $translatorNew = $translator->withResources(
            resources: $resources
        );
        
        $this->assertFalse($translator === $translatorNew);
        $this->assertTrue($resources === $translatorNew->resources());
    }
    
    public function testThatImplementsModifiersAware()
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
            localeFallbacks: ['de' => 'en'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $this->assertInstanceOf(
            ModifiersAware::class,
            $translator
        );     
    }
    
    public function testModifiersMethod()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'fr'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $this->assertInstanceOf(
            ModifiersInterface::class,
            $translator->modifiers()
        );     
    }
    
    public function testWithModifiersMethod()
    {
        $translator = new Translator(
            resources: new Resources(),
            modifiers: new Modifiers(),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'fr'],
            localeMapping: ['de' => 'de-CH'],
        );
        
        $modifiers = new Modifiers();
        
        $translatorNew = $translator->withModifiers(
            modifiers: $modifiers
        );
        
        $this->assertFalse($translator === $translatorNew);
        $this->assertTrue($modifiers === $translatorNew->modifiers());
    }
}