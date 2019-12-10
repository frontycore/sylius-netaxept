<?php

declare(strict_types=1);

namespace Fronty\SyliusNetaxeptPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Ondrej Seliga <ondrej@seliga.cz>
 */
final class FrontySyliusNetaxeptPlugin extends Bundle
{
    use SyliusPluginTrait;
}
