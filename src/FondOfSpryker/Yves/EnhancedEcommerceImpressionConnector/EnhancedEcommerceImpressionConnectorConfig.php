<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector;

use FondOfSpryker\Shared\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class EnhancedEcommerceImpressionConnectorConfig extends AbstractBundleConfig
{
    /**
     * Returns list with array-keys which should not deleted even if they are empty
     *
     * @return array
     */
    public function getDontUnsetArrayIndex(): array
    {
        return $this->get(EnhancedEcommerceImpressionConnectorConstants::CONFIG_DONT_UNSET_ARRAY_INDEX, []);
    }
}
