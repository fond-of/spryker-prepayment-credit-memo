<?php

namespace FondOfSpryker\Zed\PrepaymentCreditMemo;

use FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToCreditMemoBridge;
use FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToRefundBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class PrepaymentCreditMemoDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_REFUND = 'refund facade';

    public const FACADE_CREDIT_MEMO = 'FACADE_CREDIT_MEMO';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addRefundFacade($container);
        $container = $this->addCreditMemoFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundFacade(Container $container)
    {
        $container[self::FACADE_REFUND] = function (Container $container) {
            return new PrepaymentCreditMemoToRefundBridge($container->getLocator()->refund()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCreditMemoFacade(Container $container)
    {
        $container[self::FACADE_CREDIT_MEMO] = function (Container $container) {
            return new PrepaymentCreditMemoToCreditMemoBridge($container->getLocator()->creditMemo()->facade());
        };

        return $container;
    }
}
