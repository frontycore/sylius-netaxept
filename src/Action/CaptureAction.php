<?php

declare(strict_types=1);

namespace Fronty\SyliusNetaxeptPlugin\Action;

use Payum\Core\ApiAwareInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Request\GetHttpRequest;
use FDM\Netaxept\Api;
use FDM\Netaxept\Exception\Exception;

/**
 * @author Ondrej Seliga <ondrej@seliga.cz>
 */
class CaptureAction implements ActionInterface, ApiAwareInterface
{
	use GatewayAwareTrait;
	use ApiAwareTrait;

	public function __construct()
	{
		$this->apiClass = Api::class;
	}

	/**
	 * @param Capture $request
	 * @throws RequestNotSupportedException
	 */
	public function execute($request)
	{
		RequestNotSupportedException::assertSupports($this, $request);

		$model = $request->getModel();
		$model = ArrayObject::ensureArrayObject($model);

		if (!isset($_GET['responseCode'])) {
			$this->api->registerTransaction((array)$model);
			$model['redirectUrl'] = $request->getToken()->getAfterUrl();
			throw new HttpPostRedirect(
				$this->api->getTerminalUri($model['transactionId']),
				(array)$model
			);
		} else {
	        if (strtolower($_GET['responseCode']) === 'ok') {
	        	try {
	        		$this->api->authorize((array) $model);
	        		$this->api->capture((array) $model);
	        	} catch (Exception $e) {
	        		// Do nothing
	        	}
	        }
		}
	}

	/**
     * {@inheritdoc}
     */
    public function supports($request) {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess;
    }
}