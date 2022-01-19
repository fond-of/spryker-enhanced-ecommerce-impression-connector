<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Renderer;

use FondOfSpryker\Shared\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConstants as ModuleConstants;
use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig;
use Generated\Shared\Transfer\EcImpressionsTransfer;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class ProductImpressionRenderer implements EnhancedEcommerceRendererInterface
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
     * @var \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig
     */
    protected $config;

    /**
     * @param \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface $currencyClient
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig $config
     */
    public function __construct(
        EnhancedEcommerceImpressionConnectorToCurrencyClientInterface $currencyClient,
        MoneyPluginInterface $moneyPlugin,
        EnhancedEcommerceImpressionConnectorConfig $config
    ) {
        $this->currencyClient = $currencyClient;
        $this->moneyPlugin = $moneyPlugin;
        $this->config = $config;
    }

    /**
     * @param \Twig\Environment $twig
     * @param string            $page
     * @param array             $twigVariableBag
     *
     * @return string
     */
    public function render(\Twig\Environment $twig, string $page, array $twigVariableBag): string
    {
        $ecImpressionsTransfer = new EcImpressionsTransfer();
        $ecImpressionsTransfer->setCurrencyCode($this->currencyClient->getCurrent()->getCode());

        $this->addProducts($page, $twigVariableBag, $ecImpressionsTransfer);

        return $twig->render($this->getTemplate(), [
            'enhancedEcommece' => $ecImpressionsTransfer->toArray(true, true),
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@EnhancedEcommerceImpressionConnector/partials/product-impressions.js.twig';
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
                ->setId($this->getProductSku($product))
                ->setName($this->getProductName($product))
                ->setPrice('' . $this->getProductPrice($product) . '')
                ->setVariant($this->getProductAttrStyle($product))
                ->setList(isset($twigVariableBag[ModuleConstants::PARAM_LIST]) ? $twigVariableBag[ModuleConstants::PARAM_LIST] : $pageType)
                ->setPosition($index + 1);

            $ecImpressionsTransfer->addImpressions($enhancedEcommerceProductTransfer);
        }

        return $ecImpressionsTransfer;
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function getProductSku(array $product): string
    {
        if (isset($product[ModuleConstants::PARAM_PRODUCT_SKU])) {
            return $product[ModuleConstants::PARAM_PRODUCT_SKU];
        }

        if (isset($product[ModuleConstants::PARAM_PRODUCT_ABSTRACT_SKU])) {
            return str_replace('ABSTRACT-', '', strtoupper($product[ModuleConstants::PARAM_PRODUCT_ABSTRACT_SKU]));
        }

        return '';
    }

    /**
     * @param array $product
     *
     * @return string
     */
    protected function getProductName(array $product): string
    {
        if (
            isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED]) &&
            !empty($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED])
        ) {
            return $product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED];
        }

        if (
            isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL]) &&
            !empty($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL])
        ) {
            return $product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_MODEL];
        }

        if (
            isset($product[ModuleConstants::PARAM_PRODUCT_ABSTRACT_NAME]) &&
            !empty($product[ModuleConstants::PARAM_PRODUCT_ABSTRACT_NAME])
        ) {
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
        if (
            isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED]) &&
            !empty($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED])
        ) {
            return $product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED];
        }

        if (
            isset($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE]) &&
            !empty($product[ModuleConstants::PARAM_PRODUCT_ATTR][ModuleConstants::PARAM_PRODUCT_ATTR_STYLE])
        ) {
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
