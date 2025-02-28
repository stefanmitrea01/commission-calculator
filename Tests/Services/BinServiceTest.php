<?php

namespace refactor\calculator\Tests\Services;

use Dotenv\Dotenv;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use refactor\calculator\Services\BinService;
use GuzzleHttp\Client;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class BinServiceTest extends TestCase
{
    private Client $clientMock;
    private BinService $binService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->clientMock = Mockery::mock(Client::class);
        $this->binService = new BinService($this->clientMock);
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testIsEu(): void
    {
        $bin = '45717360';
        $responseBody = '{"country":{"alpha2":"DE"}}';

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
            ->with($_ENV['BIN_LOOKUP_URL'] . $bin)
            ->andReturn($responseMock);

        $isEu = $this->binService->isEu($bin);

        $this->assertTrue($isEu);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }
}