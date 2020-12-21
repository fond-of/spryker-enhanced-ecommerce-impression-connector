<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency;

use Generated\Shared\Transfer\CurrencyTransfer;

interface EnhancedEcommerceImpressionConnectorToCurrencyClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent(): CurrencyTransfer;
}
