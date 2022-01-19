<?php

namespace FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Plugin\Renderer;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorFactory;
use FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Renderer\ProductImpressionRenderer;
use Twig\Environment;

class ProductImpressionsExpanderPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\EnhancedEcommerceImpressionConnectorFactory
     */
    protected $factoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Yves\EnhancedEcommerceImpressionConnector\Renderer\ProductImpressionRenderer
     */
    protected $rendererMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Twig\Environment
     */
    protected $twigMock;

    /**
     * @var \FondOfSpryker\Yves\EnhancedEcommerceExtension\Dependency\EnhancedEcommerceRenderePluginInterface
     */
    protected $plugin;

    /**
     * @var array
     */
    protected $twigVariableBag;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->factoryMock = $this->getMockBuilder(EnhancedEcommerceImpressionConnectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->rendererMock = $this->getMockBuilder(ProductImpressionRenderer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigVariableBag = include codecept_data_dir('twigVariableBag.php');

        $this->plugin = new ProductImpressionsRendererPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testIsApplicable(): void
    {
        $this->assertEquals(true, $this->plugin->isApplicable('pageType', $this->twigVariableBag));
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $this->factoryMock->expects(static::atLeastOnce())
            ->method('createProductImpressionRenderer')
            ->willReturn($this->rendererMock);

        $this->rendererMock->expects(static::atLeastOnce())
            ->method('render')
            ->willReturn('rendered template as string');

        $this->plugin->render($this->twigMock, 'pageType', $this->twigVariableBag);
    }
}
