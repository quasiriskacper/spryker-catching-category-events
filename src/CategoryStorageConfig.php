<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace quasiris\CategoryStorage;

use Synchronization\SynchronizationConfig;
use Spryker\Zed\CategoryStorage\CategoryStorageConfig as SprykerCategoryStorageConfig;

class CategoryStorageConfig extends SprykerCategoryStorageConfig
{
    /**
     * @return string|null
     */
    public function getCategoryTreeSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }

    /**
     * @return string|null
     */
    public function getCategoryNodeSynchronizationPoolName(): ?string
    {
        return SynchronizationConfig::DEFAULT_SYNCHRONIZATION_POOL_NAME;
    }
}
