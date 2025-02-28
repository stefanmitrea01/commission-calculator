<?php
namespace refactor\calculator\Models;

class TransactionModel
{
    private string $bin;
    private float $amount;
    private string $currency;

    /**
     * @param  string  $bin
     * @param  float  $amount
     * @param  string  $currency
     */
    public function __construct(string $bin, float $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getBin(): string
    {
        return $this->bin;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
