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
use Tobento\Service\Translation\ResourceFactory;
use Tobento\Service\Translation\ResourceFactoryInterface;
use Tobento\Service\Translation\ResourceInterface;
use Tobento\Service\Filesystem\File;

/**
 * ResourceFactoryTest tests
 */
class ResourceFactoryTest extends TestCase
{    
    public function testThatImplementsResourceFactoryInterface()
    {
        $this->assertInstanceOf(
            ResourceFactoryInterface::class,
            new ResourceFactory()
        );     
    }

    public function testCreateResourceMethod()
    {
        $factory = new ResourceFactory();
        
        $resource = $factory->createResource(
            name: 'users',
            locale: 'en', 
            translations: ['Hello World' => 'Hello World'],
            group: 'front',
            priority: 10,        
        );
        
        $this->assertInstanceOf(
            ResourceInterface::class,
            $resource
        ); 
    }
    
    public function testCreateResourceFromFileMethod()
    {
        $factory = new ResourceFactory();
        
        $resource = $factory->createResourceFromFile(
            file: new File(__DIR__.'/trans/front/en/en.json'),
            locale: 'en',
            group: 'front',
            priority: 10,        
        );
        
        $this->assertInstanceOf(
            ResourceInterface::class,
            $resource
        ); 
    }
    
    public function testCreateResourceFromFileMethodWithStringFile()
    {
        $factory = new ResourceFactory();
        
        $resource = $factory->createResourceFromFile(
            file: __DIR__.'/trans/front/en/en.json',
            locale: 'en',
            group: 'front',
            priority: 10,        
        );
        
        $this->assertInstanceOf(
            ResourceInterface::class,
            $resource
        ); 
    }    
}