<?php

namespace refactor\calculator\Tests\Services;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use refactor\calculator\Services\CurrencyService;
use GuzzleHttp\Client;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CurrencyServiceTest extends TestCase
{
    private Client $clientMock;
    private CurrencyService $currencyService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->clientMock = Mockery::mock(Client::class);
        $this->currencyService = new CurrencyService($this->clientMock);
    }

    /**
     * @return void
     */
    public function testGetExchangeRate(): void
    {
        $fromCurrency = 'USD';
        $toCurrency = 'EUR';
        $responseBody = '{"rates":{"EUR":0.85}}';

        $responseMock = Mockery::mock(ResponseInterface::class);
        $streamMock = Mockery::mock(StreamInterface::class);

        $responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn($streamMock);

        $streamMock->shouldReceive('getContents')
            ->once()
            ->andReturn($responseBody);
        $this->clientMock->shouldReceive('get')
            ->once()
            ->with($_ENV['EXCHANGE_RATE_URL'], [
                'query' => ['base' => $fromCurrency, 'symbols' => $toCurrency],
                'headers' => ['apikey' => $_ENV['API_KEY']]
            ])
            ->andReturn($responseMock);

        $rate = $this->currencyService->getExchangeRate($fromCurrency, $toCurrency);

        $this->assertEquals(0.85, $rate);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }
}