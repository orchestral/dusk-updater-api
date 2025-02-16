<?php

namespace Orchestra\DuskUpdaterApi;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;

class HttpClient
{
    /**
     * The HTTP Client implementation.
     */
    public Client
    /**
     * HTTP Proxy configuration.
     */
    public static ?string $proxy = null;

    /**
     * SSL Verification configuration.
     */
    public static bool $verifySsl = true;

    public function __construct(
        public Client $client = new Client,
    ) {}

    /**
     * Download from URL.
     *
     * @throws \Exception
     */
    public function download(string $url, string $destination): void
    {
        $resource = Utils::tryFopen($destination, 'w');

        $response = $this->client->get($url, array_merge([
            'sink' => $resource,
            'verify' => static::$verifySsl,
        ], array_filter([
            'proxy' => static::$proxy,
        ])));

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new Exception(sprintf('Unable to download from [%s]', $url));
        }
    }

    /**
     * Get contents from URL.
     *
     * @throws \Exception
     */
    public function fetch(string $url): string
    {
        $response = $this->client->get($url, array_merge([
            'verify' => static::$verifySsl,
        ], array_filter([
            'proxy' => static::$proxy,
        ])));

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            throw new Exception(sprintf('Unable to fetch contents from [%s]', $url));
        }

        return (string) $response->getBody();
    }

    public static function __callStatic(string $method, array $parameters)
    {
        return call_user_func(new static, $method, ...$parameters);
    }
}
