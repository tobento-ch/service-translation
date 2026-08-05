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

namespace Tobento\Service\Translation\MissingHandler;

use Psr\Log\LoggerInterface;
use Tobento\Service\Translation\MissingTranslationHandler;

/**
 * A handler that performs no action for missing translations.
 *
 * This handler intentionally suppresses all logging or processing and
 * simply returns the provided translation unchanged. Useful when you
 * want to disable missing‑translation handling entirely while still
 * satisfying the MissingTranslationHandlerInterface contract.
 */
class NullHandler extends MissingTranslationHandler
{
    public function __construct()
    {
        parent::__construct(null);
    }
}