<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Renderer\ProductImpressionRenderer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\Container;

class EnhancedEcommerceImpressionConnectorFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Yves\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPluginMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface
     */
    protected $currencyClientMock;

    /**
     * @var EnhancedEcommerceImpressionConnectorFactory
     */
    protected $factory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig
     */
    protected $configMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->moneyPluginMock = $this->getMockBuilder(MoneyPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyClientMock = $this->getMockBuilder(EnhancedEcommerceImpressionConnectorToCurrencyClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(EnhancedEcommerceImpressionConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory = new EnhancedEcommerceImpressionConnectorFactory();
        $this->factory->setContainer($this->containerMock);
        $this->factory->setConfig($this->configMock);
    }

    /**
     * @return void
     */
    public function testCreateProductImpressionRenderer(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->currencyClientMock, $this->moneyPluginMock, $this->configMock);

        $this->assertInstanceOf(
            ProductImpressionRenderer::class,
            $this->factory->createProductImpressionRenderer()
        );
    }

    /**
     * @return void
     */
    public function testGetMoneyPlugin(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->moneyPluginMock);

        $this->factory->getMoneyPlugin();
    }

    /**
     * @return void
     */
    public function testGetCurrencyClient(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($this->currencyClientMock);

        $this->factory->getCurrencyClient();
    }
}
