<?php

declare(strict_types=1);

namespace Fronty\SyliusNetaxeptPlugin;

use Fronty\SyliusNetaxeptPlugin\Action\CaptureAction;
use Fronty\SyliusNetaxeptPlugin\Action\ConvertPaymentAction;
use Fronty\SyliusNetaxeptPlugin\Action\StatusAction;
use Payum\Core\GatewayFactory;
use Payum\Core\Bridge\Spl\ArrayObject;
use FDM\Netaxept\Api;

/**
 * @author Ondrej Seliga <ondrej@seliga.cz>
 */
class SyliusNetaxeptGatewayFactory extends GatewayFactory
{
	/**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'netaxept',
            'payum.factory_title' => 'netaxept',
            'payum.action.status' => new StatusAction(),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.capture' => new CaptureAction()
        ]);

        if ($config['payum.api'] == false) {
            // Netaxept default options
            $config['payum.default_options'] = [
                'merchantId' => '',
                'token' => '',
                'sandbox' => ''
            ];
            $config->defaults($config['payum.default_options']);

            // Required fields
            $config['payum.required_options'] = ['merchantId', 'token', 'sandbox'];

            $config['payum.api'] = function(ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api(
                    $config['merchantId'],
                    $config['token'],
                    NULL, NULL, NULL,
                    (bool) $config['sandbox']
                );
            };
        }
    }
}