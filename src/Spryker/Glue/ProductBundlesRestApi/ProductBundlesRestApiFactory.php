<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductBundleStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\Dependency\Client\ProductBundlesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\Dependency\RestResource\ProductBundlesRestApiToOrdersRestApiResourceInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\Expander\BundledProductExpander;
use Spryker\Glue\ProductBundlesRestApi\Processor\Expander\BundledProductExpanderInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\Mapper\OrderMapper;
use Spryker\Glue\ProductBundlesRestApi\Processor\Mapper\OrderMapperInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReader;
use Spryker\Glue\ProductBundlesRestApi\Processor\Reader\BundledProductReaderInterface;
use Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilder;
use Spryker\Glue\ProductBundlesRestApi\Processor\RestResponseBuilder\BundledProductRestResponseBuilderInterface;

/**
 * @method \Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig getConfig()
 */
class ProductBundlesRestApiFactory extends AbstractFactory
{
    public function createBundledProductExpander(): BundledProductExpanderInterface
    {
        return new BundledProductExpander($this->createBundledProductReader());
    }

    public function createBundledProductReader(): BundledProductReaderInterface
    {
        return new BundledProductReader(
            $this->getProductStorageClient(),
            $this->getProductBundleStorageClient(),
            $this->createBundledProductRestResponseBuilder(),
        );
    }

    public function createOrderMapper(): OrderMapperInterface
    {
        return new OrderMapper($this->getOrdersRestApiResource());
    }

    public function createBundledProductRestResponseBuilder(): BundledProductRestResponseBuilderInterface
    {
        return new BundledProductRestResponseBuilder($this->getResourceBuilder());
    }

    public function getProductStorageClient(): ProductBundlesRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductBundlesRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    public function getProductBundleStorageClient(): ProductBundlesRestApiToProductBundleStorageClientInterface
    {
        return $this->getProvidedDependency(ProductBundlesRestApiDependencyProvider::CLIENT_PRODUCT_BUNDLE_STORAGE);
    }

    public function getOrdersRestApiResource(): ProductBundlesRestApiToOrdersRestApiResourceInterface
    {
        return $this->getProvidedDependency(ProductBundlesRestApiDependencyProvider::RESOURCE_ORDERS_REST_API);
    }
}
