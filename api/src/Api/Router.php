<?php

namespace App\AddressNormalizer\Api;

use Psr\Http\Message\RequestInterface;

final class Router
{
    /**
     * @return array<int, string>
     */
    private static function loadRoutes(): array
    {
        return require_once __DIR__ . '/../../routes.php';
    }

    /**
     * @param RequestInterface $request
     * @return string
     * @throws AppException
     */
    public static function resolve(RequestInterface $request): string
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        $routes = self::loadRoutes();
        $action = $routes[$path] ?? null;
        if (!$action || !($action[1] ?? null)) {
            throw (new AppException('', 404))
                ->addErrors(['API: no action found for path: "' . $path . '"']);
        }

        if (($action[0] ?? null) !== $request->getMethod()) {
            throw (new AppException('', 405))
                ->addErrors(['Method not allowed']);
        }

        return $action[1];
    }
}
