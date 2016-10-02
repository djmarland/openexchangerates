<?php
namespace Djmarland\OpenExchangeRates;

use DateTimeImmutable;
use DateTimeInterface;
use Djmarland\OpenExchangeRates\Exception\ApiQuotaReachedException;
use GuzzleHttp\ClientInterface;

class Client
{
    const URL_BASE = 'https://openexchangerates.org/api/';

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

    public function getLatest(): Result
    {
        $url = $this->getUrl('latest.json');
        return $this->getResult($url);
    }

    public function getHistorical(DateTimeInterface $date): Result
    {
        $dateString = $date->format('Y-m-d');
        $url = $this->getUrl('historical/' . $dateString . '.json');
        return $this->getResult($url);
    }

    private function getUrl(string $construct)
    {
        $url = self::URL_BASE . $construct;
        $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'app_id=' . $this->apiKey;
        return $url;
    }

    private function getResult(string $url): Result
    {
        $response = $this->httpClient->request('GET', $url);
        $code = $response->getStatusCode();
        if ($code == 429) {
            throw new ApiQuotaReachedException();
        } elseif ($code !== 200) {
            throw new \Exception('Other error'); // todo - be neater
        }
        $data = json_decode($response->getBody(), true);
        return new Result(
            DateTimeImmutable::createFromFormat('U', $data['timestamp']),
            $data['base'],
            $data['rates']
        );
    }
}
