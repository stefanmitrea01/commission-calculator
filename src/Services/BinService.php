<?php
namespace refactor\calculator\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use refactor\calculator\Constants\ApiConstant;

class BinService
{
    /**
     * @param  Client  $client
     */
    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @param  string  $bin
     * @return bool
     * @throws GuzzleException
     * @throws \Exception
     */
    public function isEu(string $bin): bool
    {
        $response = $this->client->get($_ENV[ApiConstant::BIN_LOOKUP_URL] . $bin);
        $data = json_decode($response->getBody()->getContents(), true);

        if (empty($data['country']['alpha2'])) {
            throw new \Exception('Country data not found');
        }

        return in_array($data['country']['alpha2'], ApiConstant::EU_COUNTIES);
    }
}
