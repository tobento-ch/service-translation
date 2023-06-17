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
use Tobento\Service\Translation\FilesResources;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\ResourcesInterface;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\ResourceInterface;
use Tobento\Service\Translation\ResourceFactory;

/**
 * FilesResourcesTest tests
 */
class FilesResourcesTest extends TestCase
{    
    public function testThatImplementsResourcesInterface()
    {
        $this->assertInstanceOf(
            ResourcesInterface::class,
            new FilesResources(new Dirs())
        );     
    }

    public function testConstructorWithResourceFactory()
    {        
        $resources = new FilesResources(
            new Dirs(),
            new ResourceFactory()
        );
            
        $this->assertInstanceOf(
            ResourcesInterface::class,
            $resources
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
        
        $resources = new FilesResources(new Dirs());
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
        
        $resources = new FilesResources(new Dirs());
        $resources->add($subresources);
            
        $this->assertSame(
            'shop',
            $resources->locale('en')->all()[0]->name()
        );
    }    
    
    public function testFilterMethod()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
                        ->dir(dir: __DIR__.'/trans/back/', group: 'back', priority: 15)
        );

        $newResources = $resources->locale('en')->filter(
            fn(ResourceInterface $r): bool => $r->group() === 'front'
        );
        
        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(5, count($newResources->all()));
    }
    
    public function testLocaleMethod()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
                        ->dir(dir: __DIR__.'/trans/back/', group: 'back', priority: 15)
        ); 

        $newResources = $resources->locale('de-CH');

        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(1, count($newResources->all()));
        
        $this->assertSame(
            'shop',
            $newResources->all()[0]->name()
        );
    }
    
    public function testLocalesMethod()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
                        ->dir(dir: __DIR__.'/trans/back/', group: 'back', priority: 15)
        ); 

        $newResources = $resources->locales(['de-CH', 'fr']);

        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(1, count($newResources->all()));
        
        $this->assertSame(
            'shop',
            $newResources->all()[0]->name()
        );       
    }
    
    public function testNameMethod()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
                        ->dir(dir: __DIR__.'/trans/back/', group: 'back', priority: 15)
        );

        $newResources = $resources->locale('de-CH')->name('shop');

        $this->assertFalse($resources === $newResources);
        
        $this->assertSame(1, count($newResources->all()));
        
        $this->assertSame(
            'shop',
            $newResources->all()[0]->name()
        );
    }
    
    public function testSortMethod()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
                        ->dir(dir: __DIR__.'/trans/back/', group: 'back', priority: 15)
        );

        $newResources = $resources->locale('en')->sort();

        $this->assertFalse($resources === $newResources);
        
        $names = [];
        
        foreach($newResources->all() as $resource) {
            $names[] = $resource->name();
        }
        
        $this->assertSame(
            ['*', '*', '*', '*', 'shop', '*', '*', '*', '*', 'shop', 'user'],
            $names
        );     
    }

    public function testSortMethodWithCallback()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
        );

        $newResources = $resources->locale('en')->sort(
            fn(ResourceInterface $a, ResourceInterface $b): int => $a->name() <=> $b->name()
        );

        $this->assertFalse($resources === $newResources);
        
        $names = [];
        
        foreach($newResources->all() as $resource) {
            $names[] = $resource->name();
        }
        
        $this->assertSame(
            ['*', '*', '*', '*', 'shop'],
            $names
        );     
    }
    
    public function testAllMethod()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
        );

        $resource = new Resource(
            name: 'validation', 
            locale: 'de-CH', 
            translations: ['Hello World' => 'Hallo Welt'],
            group: 'front',
            priority: 8,
        );

        $resources->add($resource);  
        
        $names = [];
        
        foreach($resources->locale('de-CH')->all() as $resource) {
            $names[] = $resource->name();
        }
        
        $this->assertSame(
            ['validation', 'shop'],
            $names
        );     
    }
    
    public function testTranslationsMethod()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
        );
        
        $this->assertSame(
            [
                'Using Real Message' => 'Shop - Using Real Message',
                'usingKeywordMessage' => 'Shop - Using Keyword Messages',
            ],
            $resources->locale('en')->translations()
        );
    }
    
    public function testTranslationsMethodWithSortByPriorityOverwrites()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
                        ->dir(dir: __DIR__.'/trans/back/', group: 'back', priority: 15)
        );
        
        $this->assertSame(
            [
                'Using Real Message' => 'Back User - Using Real Message',
                'usingKeywordMessage' => 'Back User - Using Keyword Messages',
            ],
            $resources->locale('en')->sort()->translations()
        );     
    }
    
    public function testTranslationsMethodWithDottedFilenamesAreMerged()
    {
        $resources = new FilesResources(
            (new Dirs())->dir(dir: __DIR__.'/trans/front/', group: 'front', priority: 10)
        );
        
        $this->assertSame(
            [
                'team' => 'team',
                'about' => 'about',
            ],
            $resources->locale('en-US')->name('routes')->translations()
        );
    }
}