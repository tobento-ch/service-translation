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
 * HasResources
 */
trait HasResources
{    
    /**
     * @var ResourcesInterface
     */
    protected ResourcesInterface $resources;
    
    /**
     * Set the resources.
     *
     * @param ResourcesInterface $resources
     * @return static $this
     */
    protected function setResources(ResourcesInterface $resources): static
    {
        $this->resources = $resources;
        return $this;
    }
    
    /**
     * Returns the resources.
     *
     * @return ResourcesInterface
     */
    public function resources(): ResourcesInterface
    {
        return $this->resources;
    }
    
    /**
     * Returns the translations of the specified resource.
     *
     * @param string $name The resource name.
     * @param null|string $locale The locale or null to use the default.
     * @return array<string, string> The resource translations.
     */
    public function getResource(string $name, null|string $locale = null): array
    {
        return $this->resources()->locale($locale)->name($name)->translations();
    }
}