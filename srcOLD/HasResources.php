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
     * Returns the resources or null.
     *
     * @return ResourcesInterface
     */
    public function resources(): ResourcesInterface
    {
        return $this->resources;
    }   
}