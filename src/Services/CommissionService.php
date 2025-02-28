<?php

namespace refactor\calculator\Services;

use GuzzleHttp\Exception\GuzzleException;
use refactor\calculator\Constants\ApiConstant;
use refactor\calculator\Models\TransactionModel;

class CommissionService
{
    /**
     * @param  BinService  $binService
     * @param  CurrencyService  $currencyService
     */
    public function __construct(
        private readonly BinService $binService,
        private readonly CurrencyService $currencyService
    ) {
    }

    /**
     * @param  string  $filePath
     * @return array
     * @throws \Exception
     */
    public function parseInputFile(string $filePath): array
    {
        $transactions = [];
        // Read file content and split it by new lines
        foreach (explode("\n", file_get_contents($filePath)) as $row) {
            if (empty($row)) {
                continue;
            }

            $transactionData = json_decode($row, true);
            $bin = $transactionData['bin'] ?? null;
            $amount = $transactionData['amount'] ?? null;
            $currency = $transactionData['currency'] ?? null;

            // Validate required fields
            if (!$bin || !$amount || !$currency) {
                throw new \Exception("Missing required fields");
            }

            // Create a new Transaction object and add it to the list
            $transactions[] = new TransactionModel(
                $transactionData['bin'],
                $transactionData['amount'],
                $transactionData['currency']
            );
        }

        return $transactions;
    }

    /**
     * @param  TransactionModel  $transaction
     * @return float
     * @throws GuzzleException
     */
    public function calculateCommission(TransactionModel $transaction): float
    {
        // Check if the BIN corresponds to an EU country
        $isEu = $this->binService->isEu($transaction->getBin());

        // Get exchange rate for the given currency to BASE_CURRENCY_TO_CONVERT
        $rate = $this->currencyService->getExchangeRate(
            $transaction->getCurrency(),
            ApiConstant::BASE_CURRENCY_TO_CONVERT
        );

        // Convert transaction amount to BASE_CURRENCY_TO_CONVERT
        $amountInEur = $transaction->getAmount() / $rate;

        // If already in EUR or exchange rate is unavailable, keep the amount as is
        if ($transaction->getCurrency() === 'EUR' || !$rate) {
            $amountInEur = $transaction->getAmount();
        }

        // Apply different commission rates based on EU status
        $commission = $isEu ?
            $amountInEur * ApiConstant::COMMISSION_VALUE_EU :
            $amountInEur * ApiConstant::COMMISSION_VALUE_NON_EU;

        // Round up to two decimal places
        return ceil($commission * 100) / 100;
    }
}
