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

namespace Tobento\Service\Translation;

/**
 * ResourcesInterface
 */
interface ResourcesInterface
{
    /**
     * Adds a resource or resources.
     *
     * @param ResourceInterface|ResourcesInterface $resource
     * @return static $this
     */    
    public function add(ResourceInterface|ResourcesInterface $resource): static;
    
    /**
     * Returns a new instance with the resources filtered.
     *
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): static;
    
    /**
     * Returns a new instance with the specified locale.
     *
     * @param string $locale
     * @return static
     */    
    public function locale(string $locale): static;
    
    /**
     * Returns a new instance with the specified locales.
     *
     * @param array $locales
     * @return static
     */    
    public function locales(array $locales): static;
    
    /**
     * Returns a new instance with the specified name.
     *
     * @param string $name
     * @return static
     */    
    public function name(string $name): static;   
    
    /**
     * Returns a new instance with the resources sorted.
     *
     * @param null|callable $callback If null, sorts by priority, highest first.
     * @return static
     */    
    public function sort(null|callable $callback = null): static;   
    
    /**
     * Returns all resources.
     *
     * @return array<int, ResourceInterface>
     */    
    public function all(): array;
    
    /**
     * Returns all translations from the resources.
     *
     * @return array<string, string>
     */    
    public function translations(): array;
}