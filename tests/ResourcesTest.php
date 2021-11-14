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
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\ResourcesInterface;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\ResourceInterface;

/**
 * ResourcesTest tests
 */
class ResourcesTest extends TestCase
{    
    public function testThatImplementsResourcesInterface()
    {
        $this->assertInstanceOf(
            ResourcesInterface::class,
            new Resources()
        );     
    }
    
    public function testAddResourceByConstructor()
    {
        $resource = new Resource(
            name: '*', 
            locale: 'en', 
            translations: ['Hello World' => 'Hallo Welt'],
            group: 'front',
            priority: 10,
        );
        
        $resources = new Resources($resource);
        
        $this->assertSame(
            $resource,
            $resources->all()[0]
        );
    }

    public function testAddMethod()
    {
        $resource = new Resource(
            name: '*', 
            locale: 'en', 
            translations: ['Hello World' => 'Hallo Welt'],
            group: 'front',
            priority: 10,
        );
        
        $resources = new Resources();
        $resources->add($resource);
            
        $this->assertSame(
            $resource,
            $resources->all()[0]
        );
    }
    
    public function testAddMethodWithResources()
    {
        $subresources = new Resources(
            new Resource(
                name: 'shop', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
                group: 'front',
            ),  
        );
        
        $resources = new Resources();
        $resources->add($subresources);
            
        $this->assertSame(
            'shop',
            $resources->locale('en')->all()[0]->name()
        );
    }    
    
    public function testFilterMethod()
    {
        $resources = new Resources(
            new Resource(
                name: '*', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
                group: 'front',
            ),
            new Resource(
                name: '*', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
                group: 'back',
            ),    
        ); 

        $newResources = $resources->filter(
            fn(ResourceInterface $r): bool => $r->group() === 'front'
        );
        
        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(1, count($newResources->all()));
        
        $this->assertSame(
            'front',
            $newResources->all()[0]->group()
        );
    }
    
    public function testLocaleMethod()
    {
        $resources = new Resources(
            new Resource(
                name: '*', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
            ),
            new Resource(
                name: '*', 
                locale: 'de', 
                translations: ['Hello World' => 'Hallo Welt'],
            ),    
        ); 

        $newResources = $resources->locale('de');

        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(1, count($newResources->all()));
        
        $this->assertSame(
            'de',
            $newResources->all()[1]->locale()
        );
    }
    
    public function testLocalesMethod()
    {
        $resources = new Resources(
            new Resource(
                name: '*', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
            ),
            new Resource(
                name: '*', 
                locale: 'de', 
                translations: ['Hello World' => 'Hallo Welt'],
            ),
            new Resource(
                name: '*', 
                locale: 'fr', 
                translations: ['Hello World' => 'Hallo Welt'],
            ),            
        ); 

        $newResources = $resources->locales(['en', 'fr']);

        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(2, count($newResources->all()));
        
        $this->assertSame(
            'en',
            $newResources->all()[0]->locale()
        );
        
        $this->assertSame(
            'fr',
            $newResources->all()[2]->locale()
        );        
    }
    
    public function testNameMethod()
    {
        $resources = new Resources(
            new Resource(
                name: 'shop', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
            ),
            new Resource(
                name: 'user', 
                locale: 'de', 
                translations: ['Hello World' => 'Hallo Welt'],
            ),    
        ); 

        $newResources = $resources->name('shop');

        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(1, count($newResources->all()));
        
        $this->assertSame(
            'shop',
            $newResources->all()[0]->name()
        );
    }
    
    public function testSortMethod()
    {
        $resources = new Resources(
            new Resource(
                name: 'shop', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
                priority: 15,
            ),
            new Resource(
                name: 'user', 
                locale: 'de', 
                translations: ['Hello World' => 'Hallo Welt'],
                priority: 10,
            ),    
        ); 

        $newResources = $resources->sort();

        $this->assertFalse($resources === $newResources);
        
        $names = [];
        
        foreach($newResources->all() as $resource) {
            $names[] = $resource->name();
        }
        
        $this->assertSame(
            ['user', 'shop'],
            $names
        );     
    }

    public function testSortMethodWithCallback()
    {
        $resources = new Resources(
            new Resource(
                name: 'user', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
                priority: 15,
            ),
            new Resource(
                name: 'shop', 
                locale: 'de', 
                translations: ['Hello World' => 'Hallo Welt'],
                priority: 10,
            ),    
        ); 

        $newResources = $resources->sort(
            fn(ResourceInterface $a, ResourceInterface $b): int => $a->name() <=> $b->name()
        );

        $this->assertFalse($resources === $newResources);
        
        $names = [];
        
        foreach($newResources->all() as $resource) {
            $names[] = $resource->name();
        }
        
        $this->assertSame(
            ['shop', 'user'],
            $names
        );     
    }
    
    public function testAllMethod()
    {
        $resources = new Resources(
            new Resource(
                name: 'user', 
                locale: 'en', 
                translations: ['Hello World' => 'Hallo Welt'],
                priority: 15,
            ),
            new Resource(
                name: 'shop', 
                locale: 'de', 
                translations: ['Hello World' => 'Hallo Welt'],
                priority: 10,
            ),    
        ); 

        $resource = new Resource(
            name: 'validation', 
            locale: 'en', 
            translations: ['Hello World' => 'Hallo Welt'],
            group: 'front',
            priority: 10,
        );

        $resources->add($resource);  
        
        $names = [];
        
        foreach($resources->all() as $resource) {
            $names[] = $resource->name();
        }
        
        $this->assertSame(
            ['user', 'shop', 'validation'],
            $names
        );     
    }
    
    public function testTranslationsMethod()
    {
        $resources = new Resources(
            new Resource(
                name: 'user', 
                locale: 'en', 
                translations: ['Hello World' => 'Hello World'],
                priority: 15,
            ),
            new Resource(
                name: 'shop', 
                locale: 'en', 
                translations: ['Product' => 'Product'],
                priority: 10,
            ),    
        ); 
        
        $this->assertSame(
            [
                'Hello World' => 'Hello World',
                'Product' => 'Product',
            ],
            $resources->translations()
        );     
    }
    
    public function testTranslationsMethodWitSortByPriorityOverwrites()
    {
        $resources = new Resources(
            new Resource(
                name: 'user', 
                locale: 'en', 
                translations: ['Hello World' => 'Hello World User'],
                priority: 15,
            ),
            new Resource(
                name: 'shop', 
                locale: 'en', 
                translations: ['Hello World' => 'Hello World Shop'],
                priority: 10,
            ),    
        ); 
        
        $this->assertSame(
            [
                'Hello World' => 'Hello World User',
            ],
            $resources->sort()->translations()
        );     
    }    
}