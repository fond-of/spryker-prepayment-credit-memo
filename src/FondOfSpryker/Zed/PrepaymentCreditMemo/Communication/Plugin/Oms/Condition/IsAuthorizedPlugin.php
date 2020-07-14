<?php

namespace FondOfSpryker\Zed\PrepaymentCreditMemo\Communication\Plugin\Oms\Condition;

use FondOfSpryker\Shared\PrepaymentCreditMemo\PrepaymentCreditMemoConstants;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\Communication\PrepaymentCreditMemoCommunicationFactory getFactory()
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\Business\PrepaymentCreditMemoFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\PrepaymentCreditMemoConfig getConfig()
 */
class IsAuthorizedPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem)
    {
        $firstName = $orderItem->getOrder()->getFirstName();
        $lastName = $orderItem->getOrder()->getLastName();

        return ($firstName !== PrepaymentCreditMemoConstants::FIRST_NAME_FOR_INVALID_TEST && $lastName !== PrepaymentCreditMemoConstants::LAST_NAME_FOR_INVALID_TEST);
    }
}
