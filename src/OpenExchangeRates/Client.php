<?php
namespace Djmarland\OpenExchangeRates;

use GuzzleHttp\ClientInterface;

class Client
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $apiKey;

    public function __construct(ClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }
}
