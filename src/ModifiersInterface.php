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
 * ModifiersInterface
 */
interface ModifiersInterface
{
    /**
     * Returns the modified message and parameters.
     *
     * @param string $message The message.
     * @param array $parameters Any parameters for the message. 
     * @return array The modified message with the parameters. [$message, $parameters];
     */    
    public function modify(string $message, array $parameters = []): array;
    
    /**
     * Add a modifier.
     *
     * @param ModifierInterface $modifier
     * @return static $this
     */    
    public function add(ModifierInterface $modifier): static;
    
    /**
     * Returns all modifiers.
     *
     * @return array<int, ModifierInterface>
     */    
    public function all(): array;
}