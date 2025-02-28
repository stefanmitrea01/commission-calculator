<?php

namespace refactor\calculator\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use refactor\calculator\Constants\ApiConstant;

class CurrencyService
{
    private array $rateCache = [];

    /**
     * @param  Client  $client
     */
    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @param  string  $fromCurrency
     * @param  string  $toCurrency
     * @return float|null
     */
    public function getExchangeRate(string $fromCurrency, string $toCurrency): ?float
    {
        // If both currencies are the same, return a conversion rate of 1.0
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        // Check if the exchange rate is already cached
        if (isset($this->rateCache[$fromCurrency][$toCurrency])) {
            return $this->rateCache[$fromCurrency][$toCurrency];
        }

        // Retrieve API URL and API key from environment variables
        $apiUrl = $_ENV[ApiConstant::EXCHANGE_RATE_URL] ?? getenv(ApiConstant::EXCHANGE_RATE_URL) ?? '';
        $apiKey = $_ENV[ApiConstant::API_KEY] ?? getenv(ApiConstant::API_KEY) ?? '';

        // Validate API credentials
        if (!$apiUrl || !$apiKey) {
            error_log("Missing API URL or API Key for exchange rate service.");
            return null;
        }

        try {
            // Make an API request to fetch the exchange rate
            $response = $this->client->get($apiUrl, [
                'query' => ['base' => $fromCurrency, 'symbols' => $toCurrency],
                'headers' => ['apikey' => $apiKey]
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            // Validate response data
            if (!isset($data['rates'][$toCurrency])) {
                error_log("Exchange rate API response is missing rates for {$toCurrency}");
                return null;
            }

            // Store the exchange rate in cache
            $rate = (float) $data['rates'][$toCurrency];
            $this->rateCache[$fromCurrency][$toCurrency] = $rate;

            return $rate;
        } catch (GuzzleException $e) {
            // Log API request errors
            error_log("Exchange rate API error: " . $e->getMessage());
            return null;
        }
    }
}
