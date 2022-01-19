<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConstants;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Dependency\EnhancedEcommerceImpressionConnectorToCurrencyClientInterface;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorConfig;
use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Twig\Environment;

class ProductImpressionRendererTest extends Unit
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
     * @var \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected $twigMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\CurrencyTransfer
     */
    protected $currencyTransferMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Renderer\ProductImpressionRenderer
     */
    protected $renderer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->moneyPluginMock = $this->getMockBuilder(MoneyPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyClientMock = $this->getMockBuilder(EnhancedEcommerceImpressionConnectorToCurrencyClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(EnhancedEcommerceImpressionConnectorConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->currencyTransferMock = $this->getMockBuilder(CurrencyTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->renderer = new ProductImpressionRenderer($this->currencyClientMock, $this->moneyPluginMock, $this->configMock);
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $this->currencyClientMock->expects(static::atLeastOnce())
            ->method('getCurrent')
            ->willReturn($this->currencyTransferMock);

        $this->currencyTransferMock->expects(static::atLeastOnce())
            ->method('getCode')
            ->willReturn('EUR');

        $this->twigMock->expects(static::atLeastOnce())
            ->method('render')
            ->willReturn('rendered template');

        $this->moneyPluginMock->expects($this->atLeastOnce())
            ->method('convertIntegerToDecimal')
            ->with(3990)
            ->willReturn(39.90);

        $this->renderer->render($this->twigMock, 'pagetype', [
            EnhancedEcommerceImpressionConnectorConstants::PARAM_PRODUCTS => [
                [
                    'position' => 1,
                    EnhancedEcommerceImpressionConnectorConstants::PARAM_LIST => 'category title',
                    EnhancedEcommerceImpressionConnectorConstants::PARAM_PRODUCT_SKU => 'SKU-000-000',
                    EnhancedEcommerceImpressionConnectorConstants::PARAM_PRODUCT_PRICE => 3990,
                    EnhancedEcommerceImpressionConnectorConstants::PARAM_PRODUCT_ATTR => [
                        EnhancedEcommerceImpressionConnectorConstants::PARAM_PRODUCT_ATTR_MODEL_UNTRANSLATED => 'untranslated model',
                        EnhancedEcommerceImpressionConnectorConstants::PARAM_PRODUCT_ATTR_STYLE_UNTRANSLATED => 'untranslated style',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testGetTemplate(): void
    {
        static::assertEquals('@EnhancedEcommerceImpressionConnector/partials/product-impressions.js.twig', $this->renderer->getTemplate());
    }
}
