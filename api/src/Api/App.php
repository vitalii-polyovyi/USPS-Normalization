<?php

namespace App\AddressNormalizer\Api;

use App\AddressNormalizer\Domain\Repository\ApiKeyRepositoryInterface;
use App\AddressNormalizer\Exceptions\AbstractHasErrorsException;
use Invoker\Invoker;
use Invoker\ParameterResolver\Container\TypeHintContainerResolver;
use Narrowspark\HttpEmitter\SapiEmitter;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class App
{
    private function __construct(private readonly ContainerInterface $container)
    {
    }

    public static function make(ContainerInterface $container): self
    {
        return new self($container);
    }

    private function initInvoker(): Invoker
    {
        $containerResolver = new TypeHintContainerResolver($this->container);
        $invoker = new Invoker(null, $this->container);
        $invoker->getParameterResolver()->prependResolver($containerResolver);

        return $invoker;
    }

    /**
     * @return void
     * @throws \Invoker\Exception\InvocationException
     * @throws \Invoker\Exception\NotCallableException
     * @throws \Invoker\Exception\NotEnoughParametersException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function run(): void
    {
        /** @var RequestInterface|ServerRequestInterface $request */
        /** @var ResponseInterface $response */
        /** @var StreamInterface $stream */

        $invoker = $this->initInvoker();
        $request = $this->container->get(RequestInterface::class);

        try {
            $this->auth($request);
            $response = $invoker->call([Router::resolve($request), 'execute']);
        } catch (AbstractHasErrorsException $e) {
            $response = $this->processExceptionWithErrors($e);
        } finally {
            $this->cleanBuffer();
        }

        $response = $this->cors($request, $response);

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }

    private function auth(RequestInterface $request): void
    {
        $authKey = str_replace('Bearer ', '', $request->getHeaderLine('Authorization'));
        if (!$authKey) {
            throw (new AppException('', 401))->addErrors(['Not Authorized']);
        }

        /** @var ApiKeyRepositoryInterface $repo */
        $repo = $this->container->get(ApiKeyRepositoryInterface::class);
        $apiKey = $repo->getByKey($authKey);

        $server = $request->getServerParams();
        $originHost = parse_url($server['HTTP_ORIGIN'] ?? '', PHP_URL_HOST);
        if (!$apiKey || !in_array($originHost, $apiKey->getDomains())) {
            throw (new AppException('', 401))->addErrors(['Not Authorized']);
        }
    }

    private function cors(RequestInterface|ServerRequestInterface $request, ResponseInterface $response)
        : ResponseInterface
    {
        $server = $request->getServerParams();
        if ($httpOrig = ($server['HTTP_ORIGIN'] ?? null)) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $httpOrig)
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Access-Control-Max-Age', '86400');
        }

        if ($request->getMethod() === 'OPTIONS') {
            $response = $response
                ->withStatus(200)
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        return $response;
    }

    private function processExceptionWithErrors(AbstractHasErrorsException $e): ResponseInterface
    {
        $code = $e->getCode();
        $response = $this->container->get(ResponseInterface::class);

        return JsonResponse::make($response, ['errors' => $e->getErrors()])
                ->withStatus($code ?: 422);
    }

    private function cleanBuffer(): void
    {
        if (ob_get_length()) {
            ob_clean();
        }
    }
}
