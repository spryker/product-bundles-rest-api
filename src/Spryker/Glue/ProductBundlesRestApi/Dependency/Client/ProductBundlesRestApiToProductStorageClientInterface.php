<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesRestApi\Dependency\Client;

interface ProductBundlesRestApiToProductStorageClientInterface
{
    /**
     * @param string $mappingType
     * @param array<string> $identifiers
     * @param string $localeName
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByMapping(string $mappingType, array $identifiers, string $localeName): array;
}
