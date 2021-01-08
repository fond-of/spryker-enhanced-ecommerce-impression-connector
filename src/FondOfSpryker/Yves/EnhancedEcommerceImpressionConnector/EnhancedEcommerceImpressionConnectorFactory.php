<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector;

use FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Expander\ImpressionDataLayerExpander;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * Class EnhancedEcommerceImpressionConnectorFactory
 *
 * @package FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector
 * @method \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig getConfig()
 */
class EnhancedEcommerceImpressionConnectorFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceDataLayerExpanderInterface
     */
    public function createImpressionDataLayerExpander(): EnhancedEcommerceDataLayerExpanderInterface
    {
        return new ImpressionDataLayerExpander(
            $this->getCurrencyClient(),
            $this->getMoneyPlugin(),
            $this->getConfig()
        );
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
