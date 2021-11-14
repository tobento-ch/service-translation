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
 * TranslatorInterface.
 */
interface TranslatorInterface
{
    /**
     * Returns true if the message can be translated, otherwise false.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param null|string $locale The locale such as de-CH.
     * @return bool
     */
    //public function canTrans(string $message, array $parameters = [], null|string $locale = null): bool;
    
    /**
     * Returns the translated message.
     *
     * @param string $message The message to translate.
     * @param array $parameters Any parameters for the message.
     * @param null|string $locale The locale such as de-CH.
     * @return string The translated message.
     */
    public function trans(string $message, array $parameters = [], null|string $locale = null): string;
}