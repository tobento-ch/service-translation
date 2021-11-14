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
 * Modifiers
 */
class Modifiers implements ModifiersInterface
{
    /**
     * @var array<int, ModifierInterface>
     */
    protected array $modifiers = [];
    
    /**
     * Create a new Modifiers.
     *
     * @param ModifierInterface $modifier
     */    
    public function __construct(
        ModifierInterface ...$modifier,
    ) {
        $this->modifiers = $modifier;
    }

    /**
     * Returns the modified message and parameters.
     *
     * @param string The message.
     * @param array Any parameters for the message. 
     * @return array The modified message with the parameters. [$message, $parameters];
     */    
    public function modify(string $message, array $parameters = []): array
    {
        foreach($this->all() as $modifier)
        {
            [$message, $parameters] = $modifier->modify($message, $parameters);
        }
        
        return [$message, $parameters];
    }
    
    /**
     * Add a modifier.
     *
     * @param ModifierInterface $modifier
     * @return static $this
     */    
    public function add(ModifierInterface $modifier): static
    {
        $this->modifiers[] = $modifier;
        return $this;
    }    
    
    /**
     * Returns all modifiers.
     *
     * @return array<int, ModifierInterface>
     */    
    public function all(): array
    {
        return $this->modifiers;
    }    
}