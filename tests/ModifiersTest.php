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
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\ModifiersInterface;
use Tobento\Service\Translation\Modifier\ParameterReplacer;
use Tobento\Service\Translation\Modifier\Pluralization;

/**
 * ModifiersTest tests
 */
class ModifiersTest extends TestCase
{    
    public function testThatImplementsModifiersInterface()
    {
        $this->assertInstanceOf(
            ModifiersInterface::class,
            new Modifiers(
                new ParameterReplacer(),
            )
        );     
    }

    public function testAddMethod()
    {
        $modifiers = new Modifiers();
        $modifier = new ParameterReplacer();       
        $modifiers->add($modifier);
            
        $this->assertSame(
            $modifier,
            $modifiers->all()[0]
        );
    }
    
    public function testAllMethod()
    {
        $modifiers = new Modifiers(new Pluralization());
        $modifier = new ParameterReplacer();       
        $modifiers->add($modifier);
            
        $this->assertSame(
            2,
            count($modifiers->all())
        );
    }

    public function testModifyMethod()
    {
        $modifiers = new Modifiers(new ParameterReplacer());
        
        [$message, $parameters] = $modifiers->modify(
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
}