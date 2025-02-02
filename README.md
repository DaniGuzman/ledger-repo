# Multi-Currency Ledger Service

This project is a **multi-currency ledger service** built using **Laravel**. It allows users to create ledgers, record transactions (debits and credits), and retrieve real-time balances for multiple currencies. We are using a relational database (PostgreSQL).

## Features
- **Multi-Currency Support**: Ledgers can support multiple currencies.
- **Transaction Recording**: Record debit and credit transactions.
- **Real-Time Balances**: Retrieve real-time balances for ledgers.
- **ACID Compliance**: Ensures data integrity with PostgreSQL.
- **Docker Support**: Containerized for easy deployment and scalability.

---

## Setup Instructions

### Prerequisites
- Docker and Docker Compose
- PHP 8.3
- Composer

### Steps
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/DaniGuzman/ledger-repo.git
   cd to the directory
2. **Composer install**
3. **Copy env**
    ```bash
   cp .env.example .env
4. **Run docker**
    ```bash
   docker-compose up -d || ./vendor/bin/sail up -d
5. **Migrations**
    ```bash
   ./vendor/bin/sail artisan migrate --seed

# Architecture Overview
    Ledgers:
        Represent individual accounts, in this early phases we did not implement **auth**.
        Can support multiple currencies.

    Currencies:
        Define the types of currencies supported by the system (e.g., USD, EUR).

    Transactions:

        Record financial transactions (debits and credits) for a ledger.
        Each transaction is associated with a specific currency and also has a unique transaction_id.

    Balances:

        Track the current balance for each ledger and currency.
        Updated in real-time as transactions are recorded.

## Database Schema

    ledgers: Stores ledger information.

    currencies: Stores supported currencies.

    ledger_currencies: Links ledgers to supported currencies.

    transactions: Records all transactions.

    balances: Tracks the current balance for each ledger and currency.

## Concurrency Handling

    Atomic Transactions: Uses database transactions to ensure data integrity.

## API ENDPOINTS
1. POST /api/ledgers -> Create a New Ledger
2. POST /api/ledgers/{ledgerId}/transactions -> Create a New Transactions
3. GET /api/ledgers/{ledgerId}/balances -> List all transactions for a specific ledger
4. GET /api/convert -> Convert between currencies
5. GET /api/documentation -> Get Swagger documentation

## TEST
1. **Run tests**:
   ```bash
    ./vendor/bin/sail artisan test || php artisan test
