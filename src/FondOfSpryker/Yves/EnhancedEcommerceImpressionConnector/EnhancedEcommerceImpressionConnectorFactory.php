<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector;

use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Expander\DataLayerExpander;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Expander\DataLayerExpanderInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class EnhancedEcommerceImpressionConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Expander\DataLayerExpanderInterface
     */
    public function createDataLayerExpander(): DataLayerExpanderInterface
    {
        return new DataLayerExpander($this->getCurrencyClient(), $this->getMoneyPlugin());
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
