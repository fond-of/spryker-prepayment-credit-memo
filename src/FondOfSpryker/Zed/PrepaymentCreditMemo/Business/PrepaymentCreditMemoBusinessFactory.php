<?php

namespace FondOfSpryker\Zed\PrepaymentCreditMemo\Business;

use FondOfSpryker\Zed\PrepaymentCreditMemo\Business\Model\Payment\Refund;
use FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToCreditMemoInterface;
use FondOfSpryker\Zed\PrepaymentCreditMemo\PrepaymentCreditMemoDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\PrepaymentCreditMemoConfig getConfig()
 */
class PrepaymentCreditMemoBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\PrepaymentCreditMemo\Business\Model\Payment\RefundInterface
     */
    public function createRefund()
    {
        return new Refund(
            $this->getRefundFacade(),
            $this->getCreditMemoFacade()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToRefundInterface
     */
    protected function getRefundFacade()
    {
        return $this->getProvidedDependency(PrepaymentCreditMemoDependencyProvider::FACADE_REFUND);
    }

    /**
     * @return \FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToCreditMemoInterface
     */
    public function getCreditMemoFacade(): PrepaymentCreditMemoToCreditMemoInterface
    {
        return $this->getProvidedDependency(PrepaymentCreditMemoDependencyProvider::FACADE_CREDIT_MEMO);
    }
}
