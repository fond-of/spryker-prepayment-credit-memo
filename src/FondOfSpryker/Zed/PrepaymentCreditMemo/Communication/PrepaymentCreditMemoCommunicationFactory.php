<?php

namespace FondOfSpryker\Zed\PrepaymentCreditMemo\Communication;

use FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToCreditMemoInterface;
use FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToOmsInterface;
use FondOfSpryker\Zed\PrepaymentCreditMemo\PrepaymentCreditMemoDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\PrepaymentCreditMemoConfig getConfig()
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\Business\PrepaymentCreditMemoFacadeInterface getFacade()
 */
class PrepaymentCreditMemoCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToOmsInterface
     */
    public function getOmsFacade(): PrepaymentCreditMemoToOmsInterface
    {
        return $this->getProvidedDependency(PrepaymentCreditMemoDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \FondOfSpryker\Zed\PrepaymentCreditMemo\Dependency\Facade\PrepaymentCreditMemoToCreditMemoInterface
     */
    public function getCreditMemoFacade(): PrepaymentCreditMemoToCreditMemoInterface
    {
        return $this->getProvidedDependency(PrepaymentCreditMemoDependencyProvider::FACADE_CREDIT_MEMO);
    }
}
