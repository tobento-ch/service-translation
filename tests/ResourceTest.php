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
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\ResourceInterface;

/**
 * ResourceTest tests
 */
class ResourceTest extends TestCase
{    
    public function testThatImplementsResourceInterface()
    {
        $this->assertInstanceOf(
            ResourceInterface::class,
            new Resource(
                name: 'users',
                locale: 'en', 
                translations: ['Hello World' => 'Hello World'],
                group: 'front',
                priority: 10,
            )
        );     
    }

    public function testNameMethod()
    {
        $resource = new Resource(
            name: 'shop',
            locale: 'en', 
            translations: ['Hello World' => 'Hello World'],
            group: 'front',
            priority: 10,
        );
            
        $this->assertSame(
            'shop',
            $resource->name()
        );
    }
    
    public function testLocaleMethod()
    {
        $resource = new Resource(
            name: 'shop',
            locale: 'en', 
            translations: ['Hello World' => 'Hello World'],
            group: 'front',
            priority: 10,
        );
            
        $this->assertSame(
            'en',
            $resource->locale()
        );
    }
    
    public function testTranslationsMethod()
    {
        $resource = new Resource(
            name: 'shop',
            locale: 'en', 
            translations: ['Hello World' => 'Hello World'],
            group: 'front',
            priority: 10,
        );
            
        $this->assertSame(
            ['Hello World' => 'Hello World'],
            $resource->translations()
        );
    } 
    
    public function testGroupMethod()
    {
        $resource = new Resource(
            name: 'shop',
            locale: 'en', 
            translations: ['Hello World' => 'Hello World'],
            group: 'front',
            priority: 10,
        );
            
        $this->assertSame(
            'front',
            $resource->group()
        );
    } 
    
    public function testPriorityMethod()
    {
        $resource = new Resource(
            name: 'shop',
            locale: 'en', 
            translations: ['Hello World' => 'Hello World'],
            group: 'front',
            priority: 10,
        );
            
        $this->assertSame(
            10,
            $resource->priority()
        );
    }     
}