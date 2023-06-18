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
    
    /**
     * Returns a new instance with the specified resources.
     *
     * @param ResourcesInterface $resources
     * @return static
     */
    public function withResources(ResourcesInterface $resources): static;
    
    /**
     * Returns the translations of the specified resource.
     *
     * @param string $name The resource name.
     * @param null|string $locale The locale or null to use the default.
     * @return array<string, string> The resource translations.
     */
    public function getResource(string $name, null|string $locale = null): array;    
}