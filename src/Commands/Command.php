<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Routing Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\Routing\Commands;

use Framework\Routing\Router;

/**
 * Class Command.
 *
 * @package routing
 */
abstract class Command extends \Framework\CLI\Command
{
    protected Router $router;

    public function setRouter(Router $router) : static
    {
        $this->router = $router;
        return $this;
    }

    public function getRouter() : Router
    {
        return $this->router;
    }
}
