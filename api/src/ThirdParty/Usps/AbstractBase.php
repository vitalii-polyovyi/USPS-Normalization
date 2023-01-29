<?php

namespace App\AddressNormalizer\ThirdParty\Usps;

use App\AddressNormalizer\ThirdParty\Exceptions\ThirdPartyException;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;

class AbstractBase
{
    private const LIVE_URL = 'https://secure.shippingapis.com/ShippingAPI.dll';
    private const TEST_URL = 'https://production.shippingapis.com/ShippingAPITest.dll';

    private bool $isLive = false;

    public function __construct(private readonly string $username)
    {
    }

    public function live(): static
    {
        $this->isLive = true;

        return $this;
    }

    public function test(): static
    {
        $this->isLive = false;

        return $this;
    }

    private function getApiUrl(): string
    {
        return $this->isLive ? self::TEST_URL : self::LIVE_URL;
    }

    protected function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param ApiNames $apiName
     * @param string $xml
     * @return \SimpleXMLElement
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    final protected function callApi(ApiNames $apiName, string $xml): \SimpleXMLElement
    {
        $stack = HandlerStack::create();
        $stack->push(Middleware::log(logger(), new MessageFormatter()));

        $client = new Client([
            'base_uri' => $this->getApiUrl(),
            'handler' => $stack,
        ]);

        $response = $client->request('GET', '', [
            RequestOptions::QUERY => [
                'API' => $apiName->value,
                'XML' => $xml,
            ],
        ]);

        $xml = simplexml_load_string($response->getBody());
        if ($xml->getName() === 'Error') {
            $this->throwError($apiName, $xml);
        }

        return $xml;
    }

    protected function throwError(ApiNames $apiName, \SimpleXMLElement $error)
    {
        throw (new ThirdPartyException('USPS API ' . $apiName->value . ' Error', 400))
            ->addErrors([$error->Number . ': ' . $error->Description]);
    }
}
