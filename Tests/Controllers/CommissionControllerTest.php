<?php

namespace refactor\calculator\Tests\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use refactor\calculator\Controllers\CommissionController;
use refactor\calculator\Services\CommissionService;
use refactor\calculator\Models\TransactionModel;
use Mockery;

class CommissionControllerTest extends TestCase
{
    private CommissionService $commissionServiceMock;
    private CommissionController $commissionController;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->commissionServiceMock = Mockery::mock(CommissionService::class);
        $this->commissionController = new CommissionController($this->commissionServiceMock);
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testProcessTransactions()
    {
        $filePath = 'input.txt';
        $transactions = [
            new TransactionModel('45717360', 100.00, 'EUR'),
            new TransactionModel('516793', 50.00, 'USD')
        ];

        $this->commissionServiceMock->shouldReceive('parseInputFile')
            ->once()
            ->with($filePath)
            ->andReturn($transactions);

        $this->commissionServiceMock->shouldReceive('calculateCommission')
            ->times(count($transactions))
            ->andReturn(1.00, 0.50);

        $this->expectOutputString("1\n0.5\n");

        $this->commissionController->processTransactions($filePath);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }
}