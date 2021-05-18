<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector;

use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Money\Plugin\MoneyPlugin;

class EnhancedEcommerceImpressionConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';
    public const PLUGIN_MONEY = 'PLUGIN_MONEY';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addCurrencyClient($container);
        $container = $this->addMoneyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container->set(static::CLIENT_CURRENCY, function (Container $container) {
            return new EnhancedEcommerceImpressionConnectorToCurrencyClientBridge(
                $container->getLocator()->currency()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_MONEY, static function () {
            return new MoneyPlugin();
        });

        return $container;
    }
}
