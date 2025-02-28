<?php

namespace refactor\calculator\Tests\Services;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use refactor\calculator\Services\CommissionService;
use refactor\calculator\Services\BinService;
use refactor\calculator\Services\CurrencyService;
use refactor\calculator\Models\TransactionModel;
use Mockery;

class CommissionServiceTest extends TestCase
{
    private BinService $binServiceMock;
    private CurrencyService $currencyServiceMock;
    private CommissionService $commissionService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->binServiceMock = Mockery::mock(BinService::class);
        $this->currencyServiceMock = Mockery::mock(CurrencyService::class);
        $this->commissionService = new CommissionService($this->binServiceMock, $this->currencyServiceMock);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testParseInputFile(): void
    {
        $filePath = 'input.txt';
        $fileContent = '{"bin":"45717360","amount":"100.00","currency":"EUR"}' . PHP_EOL .
            '{"bin":"516793","amount":"50.00","currency":"USD"}';

        file_put_contents($filePath, $fileContent);

        $transactions = $this->commissionService->parseInputFile($filePath);

        $this->assertCount(2, $transactions);
        $this->assertEquals('45717360', $transactions[0]->getBin());
        $this->assertEquals(100.00, $transactions[0]->getAmount());
        $this->assertEquals('EUR', $transactions[0]->getCurrency());

        unlink($filePath);
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCalculateCommission(): void
    {
        $transaction = new TransactionModel('45717360', 100.00, 'EUR');

        $this->binServiceMock->shouldReceive('isEu')
            ->once()
            ->with('45717360')
            ->andReturn(true);

        $this->currencyServiceMock->shouldReceive('getExchangeRate')
            ->once()
            ->with('EUR', 'EUR')
            ->andReturn(1.0);

        $commission = $this->commissionService->calculateCommission($transaction);

        $this->assertEquals(1.00, $commission);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}