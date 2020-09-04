<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductBundleStorageClientBridge;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductStorageClientBridge;
use Spryker\Glue\ProductBundlesRestApi\Dependency\RestResource\ProductBundlesRestApiToOrdersRestApiResourceBridge;

/**
 * @method \Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig getConfig()
 */
class ProductBundlesRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    public const CLIENT_PRODUCT_BUNDLE_STORAGE = 'CLIENT_PRODUCT_BUNDLE_STORAGE';

    public const RESOURCE_ORDERS_REST_API = 'RESOURCE_ORDERS_REST_API';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addProductBundleStorageClient($container);
        $container = $this->addOrdersRestApiResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new ProductBundlesRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductBundleStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_BUNDLE_STORAGE, function (Container $container) {
            return new ProductBundlesRestApiToProductBundleStorageClientBridge(
                $container->getLocator()->productBundleStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOrdersRestApiResource(Container $container): Container
    {
        $container->set(static::RESOURCE_ORDERS_REST_API, function (Container $container) {
            return new ProductBundlesRestApiToOrdersRestApiResourceBridge(
                $container->getLocator()->ordersRestApi()->resource()
            );
        });

        return $container;
    }
}
