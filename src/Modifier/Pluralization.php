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

namespace Tobento\Service\Translation\Modifier;

use Tobento\Service\Translation\ModifierInterface;

/**
 * Pluralization
 */
class Pluralization implements ModifierInterface
{
    /**
     * Create a new Pluralization.
     *
     * @param string $key The parameter key 
     */    
    public function __construct(
        protected string $key = 'count'
    ) {}
    
    /**
     * Returns the modified message and parameters.
     *
     * @param string $message The message.
     * @param array $parameters Any parameters for the message. 
     * @return array The modified message with the parameters. [$message, $parameters];
     */    
    public function modify(string $message, array $parameters = []): array
    {
        if (empty($parameters)) {
            return [$message, $parameters];
        }
        
        if (! array_key_exists($this->key, $parameters)) {
            return [$message, $parameters];
        }
        
        // Has count.        
        $messages = explode('|', $message);
        $count = (int) $parameters[$this->key];

        if ($count > 1) { // plural
            $message = $messages[1] ?? $messages[0];
        } else {
            $message = $messages[0];
        }
        
        unset($parameters[$this->key]);
        
        return [$message, $parameters];
    }
}