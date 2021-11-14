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
use Tobento\Service\Translation\Modifier\Pluralization;

/**
 * PluralizationTest tests
 */
class PluralizationTest extends TestCase
{    
    public function testThatImplementsModifierInterface()
    {
        $this->assertInstanceOf(
            ModifierInterface::class,
            new Pluralization()
        );     
    }

    public function testModifyUsesSingular()
    {
        $modifier = new Pluralization();
        
        [$message, $parameters] = $modifier->modify(
            message: 'There is one apple|There are many apples',
            parameters: ['count' => 1],
        );
        
        $this->assertSame(
            'There is one apple',
            $message
        );
        
        $this->assertSame(
            [],
            $parameters
        );        
    }
 
    public function testModifyUsesSingularWithZeroCount()
    {
        $modifier = new Pluralization();
        
        [$message, $parameters] = $modifier->modify(
            message: 'There is one apple|There are many apples',
            parameters: ['count' => 0],
        );
        
        $this->assertSame(
            'There is one apple',
            $message
        );   
    }
    
    public function testModifyUsesPlural()
    {
        $modifier = new Pluralization();
        
        [$message, $parameters] = $modifier->modify(
            message: 'There is one apple|There are many apples',
            parameters: ['count' => 5],
        );
        
        $this->assertSame(
            'There are many apples',
            $message
        );
        
        $this->assertSame(
            [],
            $parameters
        );        
    }

    public function testModifyWithoutCountReturnsFullMessage()
    {
        $modifier = new Pluralization();
        
        [$message, $parameters] = $modifier->modify(
            message: 'There is one apple|There are many apples',
            parameters: [],
        );
        
        $this->assertSame(
            'There is one apple|There are many apples',
            $message
        );   
    }
    
    public function testModifyWithAnotherKey()
    {
        $modifier = new Pluralization('num');
        
        [$message, $parameters] = $modifier->modify(
            message: 'There is one apple|There are many apples',
            parameters: ['num' => 5],
        );
        
        $this->assertSame(
            'There are many apples',
            $message
        );   
    }    
}