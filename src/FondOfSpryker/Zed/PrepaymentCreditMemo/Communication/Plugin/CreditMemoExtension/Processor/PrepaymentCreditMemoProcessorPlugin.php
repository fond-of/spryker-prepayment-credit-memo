<?php

namespace FondOfSpryker\Zed\PrepaymentCreditMemo\Communication\Plugin\CreditMemoExtension\Processor;

use Exception;
use FondOfSpryker\Zed\CreditMemoExtension\Dependency\Plugin\CreditMemoProcessorPluginInterface;
use Generated\Shared\Transfer\CreditMemoProcessorStatusTransfer;
use Generated\Shared\Transfer\CreditMemoTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\Business\PrepaymentCreditMemoFacade getFacade()
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\PrepaymentCreditMemoConfig getConfig()
 * @method \FondOfSpryker\Zed\PrepaymentCreditMemo\Communication\PrepaymentCreditMemoCommunicationFactory getFactory()
 */
class PrepaymentCreditMemoProcessorPlugin extends AbstractPlugin implements CreditMemoProcessorPluginInterface
{
    public const NAME = 'PrepaymentCreditMemoProcessorPlugin';

    public const LISTENING_PAYMENT_PROVIDER = 'Prepayment';

    public const LISTENING_PAYMENT_METHOD = 'prepayment';

    /**
     * @param \Generated\Shared\Transfer\CreditMemoTransfer $creditMemoTransfer
     * @param \Generated\Shared\Transfer\CreditMemoProcessorStatusTransfer $statusResponse
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\CreditMemoProcessorStatusTransfer|null
     */
    public function process(CreditMemoTransfer $creditMemoTransfer, CreditMemoProcessorStatusTransfer $statusResponse): ?CreditMemoProcessorStatusTransfer
    {
        $statusResponse->setWasRefunded(false);
        if ($this->canProcess($creditMemoTransfer) === true) {
            try {
                $creditMemoTransfer->setProcessed(1);
                $creditMemoTransfer->setProcessedAt(time());

                $wasRefunded = $this->getFacade()->refundCreditMemo($creditMemoTransfer);
                $statusResponse->setWasRefunded($wasRefunded);

                if ($wasRefunded === false) {
                    throw new Exception(sprintf('Nothing was refunded for credit memo with id %s', $creditMemoTransfer->getIdCreditMemo()));
                }

                $response = $this->getFacade()->updateCreditMemo($creditMemoTransfer);

                if ($response->getIsSuccess()) {
                    $statusResponse->setSuccess(true);
                    $statusResponse->setMessage(sprintf('Processed by %s', $this->getName()));
                }

                if ($response->getIsSuccess() === false && $response->getErrors()->count() > 0) {
                    $statusResponse->setSuccess(false);
                    $errorMessages = '';
                    foreach ($response->getErrors() as $error) {
                        $errorMessages = sprintf('%s | %s', $errorMessages, $error->getMessage());
                    }
                    $statusResponse->setMessage(sprintf('Could not be processed by %s. Errors: %s', $this->getName(), $errorMessages));
                }
            } catch (Exception $exception) {
                $statusResponse->setSuccess(false);
                $statusResponse->setMessage(sprintf('Exception:%3$sMessage:%3$s%1$s%3$sTrace:%3$s%2$s', $exception->getMessage(), $exception->getTraceAsString(), PHP_EOL));
            }
        }

        return $statusResponse;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\CreditMemoTransfer $creditMemoTransfer
     *
     * @return bool
     */
    public function canProcess(CreditMemoTransfer $creditMemoTransfer): bool
    {
        $salesPaymentMethodType = $creditMemoTransfer->getSalesPaymentMethodType();

        return $salesPaymentMethodType !== null
            && $salesPaymentMethodType->getPaymentMethod() === static::LISTENING_PAYMENT_METHOD
            && $salesPaymentMethodType->getPaymentProvider() === static::LISTENING_PAYMENT_PROVIDER;
    }
}
