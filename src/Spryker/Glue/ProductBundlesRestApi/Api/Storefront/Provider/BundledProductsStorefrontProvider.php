<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\ProductBundlesRestApi\Api\Storefront\Provider;

use Generated\Api\Storefront\BundledProductsStorefrontResource;
use Generated\Shared\Transfer\ProductBundleStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductForProductBundleStorageTransfer;
use Spryker\ApiPlatform\Exception\GlueApiException;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class BundledProductsStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string MAPPING_TYPE_SKU = 'sku';

    protected const string URI_VAR_CONCRETE_SKU = 'concreteProductSku';

    public function __construct(
        protected ProductStorageClientInterface $productStorageClient,
        protected ProductBundleStorageClientInterface $productBundleStorageClient,
    ) {
    }

    /**
     * @return array<\Generated\Api\Storefront\BundledProductsStorefrontResource>
     */
    protected function provideCollection(): array
    {
        $sku = $this->resolveConcreteProductSku();

        $localeName = $this->getLocale()->getLocaleNameOrFail();
        $productConcreteIds = $this->productStorageClient->getProductConcreteIdsByMapping(
            static::MAPPING_TYPE_SKU,
            [$sku],
            $localeName,
        );

        if ($productConcreteIds === []) {
            return [];
        }

        $productBundleStorageCriteriaTransfer = (new ProductBundleStorageCriteriaTransfer())
            ->setProductConcreteIds(array_values($productConcreteIds));

        $productBundleStorageTransfers = $this->productBundleStorageClient->getProductBundles(
            $productBundleStorageCriteriaTransfer,
        );

        $resources = [];
        foreach ($productBundleStorageTransfers as $productBundleStorageTransfer) {
            foreach ($productBundleStorageTransfer->getBundledProducts() as $bundled) {
                $resources[] = $this->mapBundledToResource($bundled);
            }
        }

        return $resources;
    }

    protected function resolveConcreteProductSku(): string
    {
        if (!$this->hasUriVariable(static::URI_VAR_CONCRETE_SKU)) {
            $this->throwMissingConcreteProductSku();
        }

        $sku = (string)$this->getUriVariable(static::URI_VAR_CONCRETE_SKU);

        if ($sku === '') {
            $this->throwMissingConcreteProductSku();
        }

        return $sku;
    }

    protected function throwMissingConcreteProductSku(): never
    {
        throw new GlueApiException(
            Response::HTTP_BAD_REQUEST,
            ProductBundlesRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED,
            ProductBundlesRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED,
        );
    }

    protected function mapBundledToResource(ProductForProductBundleStorageTransfer $bundled): BundledProductsStorefrontResource
    {
        $resource = new BundledProductsStorefrontResource();
        $resource->sku = $bundled->getSku();
        $resource->quantity = $bundled->getQuantity();

        return $resource;
    }
}
