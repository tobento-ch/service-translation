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
 * ResourcesAware
 */
interface ResourcesAware
{
    /**
     * Returns the resources or null.
     *
     * @return ResourcesInterface
     */
    public function resources(): ResourcesInterface;
}