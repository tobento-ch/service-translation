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
 * HasModifiers
 */
trait HasModifiers
{    
    /**
     * @var ModifiersInterface
     */
    protected ModifiersInterface $modifiers;
    
    /**
     * Set the modifiers.
     *
     * @param ModifiersInterface $modifiers
     * @return static $this
     */
    protected function setModifiers(ModifiersInterface $modifiers): static
    {
        $this->modifiers = $modifiers;
        return $this;
    }
    
    /**
     * Returns the modifiers.
     *
     * @return ModifiersInterface
     */
    public function modifiers(): ModifiersInterface
    {
        return $this->modifiers;
    }
}