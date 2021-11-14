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
 * ModifierInterface
 */
interface ModifierInterface
{
    /**
     * Returns the modified message and parameters.
     *
     * @param string $message The message.
     * @param array $parameters Any parameters for the message. 
     * @return array The modified message with the parameters. [$message, $parameters];
     */    
    public function modify(string $message, array $parameters = []): array;
}