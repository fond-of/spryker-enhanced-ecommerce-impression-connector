<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Plugin\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRenderePluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorFactory getFactory()
 */
class ProductImpressionsRendererPlugin extends AbstractPlugin implements EnhancedEcommerceRenderePluginInterface
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
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array $twigVariableBag
     *
     * @return string
     */
    public function render(Environment $twig, string $page, array $twigVariableBag): string
    {
        return $this->getFactory()
            ->createProductImpressionRenderer()
            ->render($twig, $page, $twigVariableBag);
    }
}
