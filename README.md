# Commission Calculator

A PHP-based commission calculator that processes transactions and calculates commission fees using external API calls.

## Requirements

- PHP 8.1 or higher
- Composer (for managing dependencies)

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/stefanmitrea01/commission-calculator.git
    cd commission-calculator
    ```

2. Install dependencies using Composer:

    ```bash
    composer install
    ```

3. Set up environment variables:

   Copy content from `exemple.env` file:

   Create `.env` file and paste content from exemple.env and configure the following:

    ```
    BIN_LOOKUP_URL=https://lookup.binlist.net/
    EXCHANGE_RATE_URL=https://api.apilayer.com/exchangerates_data/latest
    API_KEY=your_api_key_here
    ```

## Usage

Run the application with:

```bash
php app.php input.txt
