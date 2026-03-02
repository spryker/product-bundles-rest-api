<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Dependency\RestResource;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;

class ProductBundlesRestApiToOrdersRestApiResourceBridge implements ProductBundlesRestApiToOrdersRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\OrdersRestApi\OrdersRestApiResourceInterface
     */
    protected $ordersRestApiResource;

    /**
     * @param \Spryker\Glue\OrdersRestApi\OrdersRestApiResourceInterface $ordersRestApiResource
     */
    public function __construct($ordersRestApiResource)
    {
        $this->ordersRestApiResource = $ordersRestApiResource;
    }

    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        return $this->ordersRestApiResource
            ->mapItemTransferToRestOrderItemsAttributesTransfer($itemTransfer, $restOrderItemsAttributesTransfer);
    }
}
