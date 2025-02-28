<?php

namespace refactor\calculator\Constants;

class ApiConstant
{
    public const BIN_LOOKUP_URL = 'BIN_LOOKUP_URL';
    public const EXCHANGE_RATE_URL = 'EXCHANGE_RATE_URL';
    public const API_KEY = 'API_KEY';
    public const BASE_CURRENCY_TO_CONVERT = 'EUR';
    public const EU_COUNTIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK'
    ];

    public const COMMISSION_VALUE_EU = 0.01;
    public const COMMISSION_VALUE_NON_EU = 0.02;
}
