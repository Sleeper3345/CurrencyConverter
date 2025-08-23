<?php

declare(strict_types=1);

namespace common\components\clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use yii\helpers\Json;

class FreeCurrencyClient
{
    private readonly Client $client;

    /**
     * FreeCurrencyClient constructor.
     * @param string $url
     * @param string $apiKey
     */
    public function __construct(private readonly string $url, private readonly string $apiKey)
    {
        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout' => 10,
            'headers' => [
                'apikey' => $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @param string $currencyFrom
     * @param array $currenciesTo
     * @return array
     */
    public function getRates(string $currencyFrom, array $currenciesTo): array
    {
        try {
            $response = $this->client->get('latest', [
                'query' => [
                    'base_currency' => $currencyFrom,
                    'currencies' => implode(',', $currenciesTo),
                ],
            ]);
        } catch (GuzzleException $e) {
            return [
                'status' => $e->getCode(),
                'data' => [],
            ];
        }

        $data = Json::decode($response->getBody()->getContents(), true);

        return [
            'status' => 200,
            'data' => $data['data'] ?? [],
        ];
    }
}
