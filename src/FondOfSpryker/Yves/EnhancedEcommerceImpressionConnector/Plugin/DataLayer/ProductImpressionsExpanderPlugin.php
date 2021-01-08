<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Plugin\DataLayer;

use FondOfSpryker\Shared\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorFactory getFactory()
 */
class ProductImpressionsExpanderPlugin extends AbstractPlugin implements EnhancedEcommerceDataLayerExpanderPluginInterface
{
    /**
     * @param string $pageType
     * @param array $twigVariableBag
     *
     * @return bool
     */
    public function isApplicable(string $pageType, array $twigVariableBag = []): bool
    {
        return isset($twigVariableBag[ModuleConstants::PARAM_PRODUCTS])
            && count($twigVariableBag[ModuleConstants::PARAM_PRODUCTS]) > 0;
    }

    /**
     * @param string $page
     * @param array $twigVariableBag
     * @param array $dataLayer
     *
     * @return array
     */
    public function expand(string $page, array $twigVariableBag, array $dataLayer): array
    {
        return $this->getFactory()
            ->createImpressionDataLayerExpander()
            ->expand($page, $twigVariableBag, $dataLayer);
    }
}
