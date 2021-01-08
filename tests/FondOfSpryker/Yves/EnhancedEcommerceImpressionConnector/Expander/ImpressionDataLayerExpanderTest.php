<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Expander;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\Container;

class ImpressionDataLayerExpanderTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPluginMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface
     */
    protected $currencyClientMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig
     */
    protected $configMock;

    /**
     * @var DataLayerExpanderInterface
     */
    protected $expander;

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

        $this->expander = new ImpressionDataLayerExpander($this->currencyClientMock, $this->moneyPluginMock, $this->configMock);
    }

    /**
     * @return void
     */
    public function testExpand(): void
    {
        $twigVariableBag = include codecept_data_dir('twigVariableBag.php');

        $this->moneyPluginMock->expects($this->atLeastOnce())
            ->method('convertIntegerToDecimal')
            ->with(3990)
            ->willReturn(39.90);

        $result = $this->expander->expand('pageTye', $twigVariableBag, []);

        $this->assertArrayHasKey('ec_impressions', $result);
    }
}
