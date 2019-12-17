<?php

declare(strict_types=1);

namespace Fronty\SyliusNetaxeptPlugin\Action;

use Payum\Core\Request\Convert;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Bridge\Spl\ArrayObject;

/**
 * @author Ondrej Seliga <ondrej@seliga.cz>
 */
class ConvertPaymentAction implements ActionInterface
{
	/**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $payment = $request->getSource();
        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $details['transactionId'] = $payment->getNumber();
        $details['description'] = $payment->getDescription();
        $details['orderNumber'] = $payment->getNumber();
        $details['currencyCode'] = $payment->getCurrencyCode();
        $details['amount'] = $payment->getTotalAmount();
        $details['language'] = 'fi_FI';
        if ($request->getToken()) {
            $details['redirectUrl'] = $request->getToken()->getTargetUrl();
        }

        $request->setResult((array) $details);
    }

	/**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() == 'array'
        ;
    }
}