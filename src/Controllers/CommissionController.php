<?php
namespace refactor\calculator\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use refactor\calculator\Services\CommissionService;

class CommissionController
{
    /**
     * @param  CommissionService  $commissionService
     */
    public function __construct(private readonly CommissionService $commissionService)
    {
    }

    /**
     * @param  string  $filePath
     * @return void
     * @throws \Exception
     * @throws GuzzleException
     */
    public function processTransactions(string $filePath): void
    {
        // Parse transactions from the input file
        $transactions = $this->commissionService->parseInputFile($filePath);

        foreach ($transactions as $transaction) {
            $commission = $this->commissionService->calculateCommission($transaction);
            echo $commission . "\n";
        }
    }
}
