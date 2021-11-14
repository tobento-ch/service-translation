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
 * ResourceInterface
 */
interface ResourceInterface
{
    /**
     * Returns the resource name.
     *
     * @return string
     */    
    public function name(): string;
    
    /**
     * Returns the resource locale.
     *
     * @return string
     */    
    public function locale(): string;

    /**
     * Returns the resource group.
     *
     * @return string
     */    
    public function group(): string;
    
    /**
     * Returns the resource priority.
     *
     * @return int
     */    
    public function priority(): int;   
    
    /**
     * Returns the resource translations.
     *
     * @return array<string, string>
     */    
    public function translations(): array;
}