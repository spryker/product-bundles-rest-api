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
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Glue\ProductBundlesRestApi\Api\Storefront\Exception\ProductBundlesExceptionFactory;
use Spryker\Service\Serializer\SerializerServiceInterface;

class BundledProductsStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string MAPPING_TYPE_SKU = 'sku';

    protected const string URI_VAR_CONCRETE_SKU = 'concreteProductSku';

    public function __construct(
        protected ProductStorageClientInterface $productStorageClient,
        protected ProductBundleStorageClientInterface $productBundleStorageClient,
        protected ProductBundlesExceptionFactory $exceptionFactory,
        protected SerializerServiceInterface $serializer,
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
                $resources[] = $this->denormalizeToResource($bundled);
            }
        }

        return $resources;
    }

    protected function resolveConcreteProductSku(): string
    {
        if (!$this->hasUriVariable(static::URI_VAR_CONCRETE_SKU)) {
            throw $this->exceptionFactory->createMissingConcreteProductSkuException();
        }

        $sku = (string)$this->getUriVariable(static::URI_VAR_CONCRETE_SKU);

        if ($sku === '') {
            throw $this->exceptionFactory->createMissingConcreteProductSkuException();
        }

        return $sku;
    }

    protected function denormalizeToResource(ProductForProductBundleStorageTransfer $bundled): BundledProductsStorefrontResource
    {
        return $this->serializer->denormalize(
            ['sku' => $bundled->getSku(), 'quantity' => $bundled->getQuantity()],
            BundledProductsStorefrontResource::class,
        );
    }
}
