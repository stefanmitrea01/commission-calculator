<?php

namespace refactor\calculator\Models;

class TransactionModel
{
    /**
     * @param  string  $bin
     * @param  float  $amount
     * @param  string  $currency
     */
    public function __construct(
        public readonly string $bin,
        public readonly float $amount,
        public readonly string $currency
    ) {
    }
}
