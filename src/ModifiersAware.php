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
 * ModifiersAware
 */
interface ModifiersAware
{
    /**
     * Returns the modifiers.
     *
     * @return ModifiersInterface
     */
    public function modifiers(): ModifiersInterface;
    
    /**
     * Returns a new instance with the specified modifiers.
     *
     * @param ModifiersInterface $modifiers
     * @return static
     */
    public function withModifiers(ModifiersInterface $modifiers): static;
}