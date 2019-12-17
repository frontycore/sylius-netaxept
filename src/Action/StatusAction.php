<?php

declare(strict_types=1);

namespace Fronty\SyliusNetaxeptPlugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use FDM\Netaxept\Api;
use FDM\Netaxept\Exception\TransactionNotFoundException;
use FDM\Netaxept\Response\QueryInterface;
use Payum\Core\Bridge\Spl\ArrayObject;

/**
 * @author Ondrej Seliga <ondrej@seliga.cz>
 */
final class StatusAction implements ActionInterface, ApiAwareInterface
{
	use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = Api::class;
    }

	/**
	 * @param GetStatusInterface $request
	 * @throws RequestNotSupportedException
	 */
	public function execute($request)
	{
		RequestNotSupportedException::assertSupports($this, $request);

		$model = ArrayObject::ensureArrayObject($request->getModel());

		if (empty($model['transactionId'])) {
            return $request->markNew();
        }

        try {
            /** @var Query $queryResponse */
            $queryResponse = $this->api->getTransaction($model['transactionId']);
        } catch (TransactionNotFoundException $e) {
            return $request->markNew();
        }

        switch ($queryResponse->getTransactionStatus()) {
            case QueryInterface::STATUS_PENDING: return $request->markPending();
            case QueryInterface::STATUS_AUTHORIZED: return $request->markAuthorized();
            case QueryInterface::STATUS_CAPTURED: return $request->markCaptured();
            case QueryInterface::STATUS_CANCELLED: return $request->markCanceled();
            case QueryInterface::STATUS_CREDITED: return $request->markRefunded();
        }

        return $request->markFailed();
	}

	/**
	 * {@inheritDoc}
	 */
	public function supports($request) {
		return $request instanceof GetStatusInterface &&
			$request->getModel() instanceof \ArrayAccess;
	}
}