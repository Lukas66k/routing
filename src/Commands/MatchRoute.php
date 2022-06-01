<?php declare(strict_types=1);
namespace Framework\Routing\Commands;

use Framework\CLI\CLI;

/**
 * Class MatchRoute.
 *
 * @package routing
 */
class MatchRoute extends Command
{
    public function run() : void
    {
        $url = $this->console->getArgument(0);

        $items = parse_url($url);
        if ($items['scheme'] === 'https') {
            $_SERVER['HTTPS'] = 'on';
        }
        $_SERVER['REQUEST_SCHEME'] = $items['scheme'];
        $_SERVER['HTTP_HOST'] = $items['host'];
        $_SERVER['REQUEST_URI'] = $items['path'] ?? '/';
        $request = $this->router->getResponse()->getRequest();
        $request->prepareStatusLine();
        $request->prepareHeaders();
        $route = $this->router->match();
        //$route->getName();
        CLI::table([
            [
                $route->getName(),
                $route->getUrl(),
                is_string($route->getAction()) ? $route->getAction() : '{closure}',
            ],
        ], ['Name', 'URL Pattern', 'Action']);
    }
}
