<?php
namespace Dealer4dealer\PaymentCostExample\Plugin;

use Closure;
use Dealer4dealer\Xcore\Model\PaymentCost;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderRepositoryInterfacePlugin
{
    protected $objectManager;

    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function aroundGet(
        OrderRepositoryInterface $subject,
        Closure $proceed,
        $orderId
    )
    {
        /** @var \Magento\Sales\Api\Data\OrderInterface $resultOrder */
        $resultOrder = $proceed($orderId);

        if ($resultOrder->getPayment()) {

            $extensionAttributes = $resultOrder->getPayment()->getExtensionAttributes();

            /** @var PaymentCost $paymentCost */
            $paymentCost = $this->objectManager->create('Dealer4dealer\Xcore\Model\PaymentCost');

            /**
             * We use hard coded values in this example. This would be
             * the place to add the payment cost of a PSP. For example
             * from the database.
             */
            $paymentCost->setData([
                'title'         => 'xCore PSPs',
                'base_amount'   => 0.25,
                'amount'        => 0.25,
                'tax_percent'   => 0,
            ]);

            $extensionAttributes->setXcorePaymentCosts([$paymentCost]);
        }

        return $resultOrder;
    }
}