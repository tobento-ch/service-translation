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
 * ParameterReplacer
 */
class ParameterReplacer implements ModifierInterface
{
    /**
     * Create a new ParameterReplacer.
     *
     * @param string $indicator
     */    
    public function __construct(
        protected string $indicator = ':'
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
        
        $replace = [];
        
        foreach($parameters as $key => $value)
        {
            if (str_starts_with($key, $this->indicator)) {
                $replace[$key] = $value;
            }
        }
        
        // Do the replacements.
        return [
            strtr($message, $replace),
            $parameters
        ];
    }
}