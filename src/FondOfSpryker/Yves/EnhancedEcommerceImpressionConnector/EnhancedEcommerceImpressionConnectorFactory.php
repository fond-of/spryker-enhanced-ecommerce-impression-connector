<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector;

use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Renderer\ProductImpressionRenderer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig getConfig()
 */
class EnhancedEcommerceImpressionConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRendererInterface
     */
    public function createProductImpressionRenderer(): EnhancedEcommerceRendererInterface
    {
        return new ProductImpressionRenderer($this->getCurrencyClient(), $this->getMoneyPlugin(), $this->getConfig());
    }

    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface
     */
    public function getCurrencyClient(): EnhancedEcommerceImpressionConnectorToCurrencyClientInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceImpressionConnectorDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    public function getMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(EnhancedEcommerceImpressionConnectorDependencyProvider::PLUGIN_MONEY);
    }
}
