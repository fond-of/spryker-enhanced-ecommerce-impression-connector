<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Expander;

use FondOfSpryker\Shared\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use Generated\Shared\Transfer\EcImpressionsTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class DataLayerExpander implements DataLayerExpanderInterface
{
    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface $currencyClient
     */
    public function __construct(
        EnhancedEcommerceImpressionConnectorToCurrencyClientInterface $currencyClient,
        MoneyPluginInterface $moneyPlugin
    ) {
        $this->currencyClient = $currencyClient;
        $this->moneyPlugin = $moneyPlugin;
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
        $ecImpressionsTransfer = new EcImpressionsTransfer();
        $ecImpressionsTransfer->setCurrencyCode($this->currencyClient->getCurrent()->getCode());

        $this->addProducts($page, $twigVariableBag, $ecImpressionsTransfer);

        return ['ec_impressions' => $ecImpressionsTransfer->toArray()];
    }

    /**
     * @param string $pageType
     * @param array $twigVariableBag
     * @param \Generated\Shared\Transfer\EcImpressionsTransfer $ecImpressionsTransfer
     *
     * @return \Generated\Shared\Transfer\EcImpressionsTransfer
     */
    protected function addProducts(string $pageType, array $twigVariableBag, EcImpressionsTransfer $ecImpressionsTransfer): EcImpressionsTransfer
    {
        foreach ($twigVariableBag[ModuleConstants::PARAM_PRODUCTS] as $index => $product) {
            $enhancedEcommerceProductTransfer = (new EnhancedEcommerceProductTransfer())
                ->setId($product[ModuleConstants::PARAM_PRODUCT_ID_PRODUCT_ABSTRACT])
                ->setName($this->getProductName($product))
                ->setPrice($this->getProductPrice($product))
                ->setVariant($this->getProductAttrStyle($product))
                ->setList(isset($twigVariableBag[ModuleConstants::PARAM_LIST]) ? $twigVariableBag[ModuleConstants::PARAM_LIST] : $pageType)
                ->setPosition($index);

            $ecImpressionsTransfer->addImpressions($enhancedEcommerceProductTransfer);
        }

        return $ecImpressionsTransfer;
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function getProductName(array $product): string
    {
        if (isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED])) {
            return $product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED];
        }

        if (isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL])) {
            return $product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL];
        }

        if (isset($product[ModuleConstants::PARAM_PRODUCT_ABSTRACT_NAME])) {
            return $product[ModuleConstants::PARAM_PRODUCT_ABSTRACT_NAME];
        }

        return '';
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function getProductAttrStyle(array $product): string
    {
        if (isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED])) {
            return $product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED];
        }

        if (isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE])) {
            return $product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE];
        }

        return '';
    }

    /**
     * @param array $product
     *
     * @return float
     */
    protected function getProductPrice(array $product): float
    {
        if (isset($product[ModuleConstants::PARAM_PRODUCT_PRICE])) {
            return $this->moneyPlugin->convertIntegerToDecimal($product[ModuleConstants::PARAM_PRODUCT_PRICE]);
        }

        return 0;
    }
}
