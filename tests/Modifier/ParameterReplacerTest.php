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

namespace Tobento\Service\Translation\Test\Modifier;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Translation\ModifierInterface;
use Tobento\Service\Translation\Modifier\ParameterReplacer;

/**
 * ParameterReplacerTest tests
 */
class ParameterReplacerTest extends TestCase
{    
    public function testThatImplementsModifierInterface()
    {
        $this->assertInstanceOf(
            ModifierInterface::class,
            new ParameterReplacer()
        );     
    }

    public function testModify()
    {
        $modifier = new ParameterReplacer();

        [$message, $parameters] = $modifier->modify(
            message: 'Hi :name',
            parameters: [':name' => 'John'],
        );
        
        $this->assertSame(
            'Hi John',
            $message
        );
        
        $this->assertSame(
            [':name' => 'John'],
            $parameters
        );        
    }
    
    public function testReplaceWithoutSpacesShouldReplace()
    {
        $modifier = new ParameterReplacer();

        [$message, $parameters] = $modifier->modify(
            message: 'Hi:nameHow are you',
            parameters: [':name' => 'John'],
        );
        
        $this->assertSame(
            'HiJohnHow are you',
            $message
        );      
    }
    
    public function testMultipleReplaces()
    {
        $modifier = new ParameterReplacer();

        [$message, $parameters] = $modifier->modify(
            message: 'Hi :name. In :minutes minutes.',
            parameters: [':name' => 'John', ':minutes' => 5],
        );
        
        $this->assertSame(
            'Hi John. In 5 minutes.',
            $message
        );      
    }
    
    public function testMissingParameter()
    {
        $modifier = new ParameterReplacer();

        [$message, $parameters] = $modifier->modify(
            message: 'Hi :name. In :minutes minutes.',
            parameters: [':name' => 'John'],
        );
        
        $this->assertSame(
            'Hi John. In :minutes minutes.',
            $message
        );      
    }
    
    public function testWithOtherIndicator()
    {
        $modifier = new ParameterReplacer('|');

        [$message, $parameters] = $modifier->modify(
            message: 'Hi |name',
            parameters: ['|name' => 'John'],
        );
        
        $this->assertSame(
            'Hi John',
            $message
        );      
    }    
}