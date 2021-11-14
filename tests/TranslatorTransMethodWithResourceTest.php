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
 * TranslatorTransMethodWithResourceTest tests
 */
class TranslatorTransMethodWithResourceTest extends TestCase
{   
    public function testWithDotNotation()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('shop', 'de', [
                    'noProducts' => 'Keine Produkte',
                    'No items in your shopping bag.' => 'Keine Artikel sind in deinem Warenkorb.',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'en'],
        );

        $translated = $translator->trans(
            message: 'shop.noProducts',
            locale: 'de'
        );
        
        $this->assertSame(
            'Keine Produkte',
            $translated
        );
    }
    
    public function testWithSrcParameter()
    {
        $translator = new Translator(
            resources: new Resources(
                new Resource('shop', 'de', [
                    'noProducts' => 'Keine Produkte',
                    'No items in your shopping bag.' => 'Keine Artikel sind in deinem Warenkorb.',
                ]),
            ),
            modifiers: new Modifiers(
                new ParameterReplacer(),
            ),
            missingTranslationHandler: new MissingTranslationHandler(),
            locale: 'en',
            localeFallbacks: ['de' => 'en'],
        );

        $translated = $translator->trans(
            message: 'No items in your shopping bag.',
            parameters: ['src' => 'shop'],
            locale: 'de'
        );
        
        $this->assertSame(
            'Keine Artikel sind in deinem Warenkorb.',
            $translated
        );
    }    
}