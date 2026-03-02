<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Dependency\RestResource;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;

interface ProductBundlesRestApiToOrdersRestApiResourceInterface
{
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer;
}
