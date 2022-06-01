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

use Framework\CLI\CLI;

/**
 * Class Routes.
 *
 * @package routing
 */
class Routes extends Command
{
    protected string $description = 'Shows routes list.';

    public function run() : void
    {
        $this->getRouter()->match();
        $collections = $this->getRouter()->getCollections();
        $count = count($collections);
        CLI::write('There ' . ($count !== 1 ? 'are' : 'is')
            . ' ' . $count . ' Route Collection' . ($count !== 1 ? 's' : '')
            . ' set.'
        );
        CLI::newLine();
        foreach ($collections as $index => $collection) {
            CLI::write('Route Collection ' . ($index + 1) . ':', CLI::FG_YELLOW);
            CLI::write(CLI::style('Origin: ', formats: [CLI::FM_BOLD]) . $collection->origin);
            $notFound = $this->router->getMatchedOrigin() ? $collection->getRouteNotFound() : false;
            if ($notFound) {
                CLI::write(CLI::style('Route Not Found: ', formats: [CLI::FM_BOLD])
                    . (is_string($notFound->getAction()) ? $notFound->getAction() : '{closure}')
                );
            }
            CLI::write(CLI::style('Routes Count: ', formats: [CLI::FM_BOLD]) . count($collection));
            $tbody = [];
            foreach ($collection->routes as $method => $routes) {
                foreach ($routes as $route) {
                    $tbody[] = [
                        'method' => $method,
                        'path' => $route->getPath(),
                        'action' => is_string($route->getAction())
                            ? $route->getAction()
                            : '{closure}',
                        'name' => $route->getName(),
                        'hasOptions' => $route->getOptions() ? 'Yes' : 'No',
                    ];
                }
            }
            \usort($tbody, static function ($str1, $str2) {
                $cmp = \strcmp($str1['path'], $str2['path']);
                if ($cmp === 0) {
                    $cmp = \strcmp($str1['method'], $str2['method']);
                }
                return $cmp;
            });
            CLI::table($tbody, ['Method', 'Path', 'Action', 'Name', 'Has Options']);
            CLI::newLine();
        }
    }
}
