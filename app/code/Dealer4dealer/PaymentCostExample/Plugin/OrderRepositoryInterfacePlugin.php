<?php
namespace Dealer4dealer\PaymentCostExample\Plugin;

use Dealer4dealer\Xcore\Model\PaymentCost;
use Psr\Log\LoggerInterface;

class OrderRepositoryInterfacePlugin
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function aroundGet(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Closure $proceed,
        $orderId
    )
    {
        /** @var \Magento\Sales\Api\Data\OrderInterface $resultOrder */
        $resultOrder = $proceed($orderId);

        if ($resultOrder->getPayment()) {

            $extensionAttributes = $resultOrder->getPayment()->getExtensionAttributes();

            /**
             * We use hard coded values in this example. This would be
             * the place to add the payment cost of a PSP. For example
             * from the database.
             */
            $paymentCost = new PaymentCost([
                'title'         => 'xCore PSPs',
                'base_amount'   => 0.25,
                'amount'        => 0.25,
                'tax_percent'   => 0,
            ]);

            $extensionAttributes->setXcorePaymentCosts($paymentCost);
        }

        return $resultOrder;
    }
}