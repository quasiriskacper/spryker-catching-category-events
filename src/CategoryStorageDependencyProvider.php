<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace quasiris\CategoryStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CategoryStorage\Dependency\Facade\CategoryStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\CategoryStorage\Dependency\QueryContainer\CategoryStorageToCategoryQueryContainerBridge;
use Spryker\Zed\CategoryStorage\Dependency\QueryContainer\CategoryStorageToLocaleQueryContainerBridge;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductFacade($container);
        $container = $this->addLocaleFacade($container);
        return $container;
    }

    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };
        return $container;
    }

    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return $container->getLocator()->locale()->facade();
        };
        return $container;
    }

}