<?php

use GuzzleHttp\Exception\GuzzleException;
use refactor\calculator\Controllers\CommissionController;
use refactor\calculator\Services\CommissionService;
use refactor\calculator\Services\BinService;
use refactor\calculator\Services\CurrencyService;
use GuzzleHttp\Client;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Client();

$binService = new BinService($client);
$currencyService = new CurrencyService($client);
$commissionService = new CommissionService($binService, $currencyService);
$controller = new CommissionController($commissionService);

if (isset($argv[1])) {
    try {
        $controller->processTransactions($argv[1]);
    } catch (GuzzleException | Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo "An error occurred while processing the request.";
    }
} else {
    echo "Please provide the input file path as an argument.\n";
}
