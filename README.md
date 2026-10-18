# Digital Wallet

A Laravel API exercise for generating bank payment-request XML and receiving transaction webhooks from Acme Bank and PayTech Bank. Incoming webhooks are persisted and queued for processing. This repository is an integration prototype, not a complete wallet ledger or funds-transfer system.

## Requirements

- Git
- Docker and [DDEV](https://ddev.com/get-started/) (recommended; this repository configures PHP 8.4 and MariaDB)
- For a native setup instead: PHP 8.4.1 or newer, Composer, and SQLite
- Apidog, if you want to run the supplied API collection

## Install and run with DDEV

```bash
git clone https://github.com/ibrahim7337/DigitalWallet.git
cd DigitalWallet
ddev start
cp .env.example .env
```

The DDEV configuration starts PHP 8.4 and MariaDB. Update the database and URL values in `.env` to use DDEV's local database:

```bash
APP_URL=https://digital-wallet.ddev.site
DB_CONNECTION=mariadb
DB_HOST=db
DB_PORT=3306
DB_DATABASE=db
DB_USERNAME=db
DB_PASSWORD=db
```

Keep `QUEUE_CONNECTION=database`, which is already set in `.env.example`. Install dependencies, generate an application key, then create and seed the database:

```bash
ddev composer install
ddev artisan key:generate
ddev artisan migrate --seed
```

The seeder creates a `Test User` (`test@example.com`). The webhook handler currently notifies user ID `1`, so the seeded user on a fresh database is used by the example workflow. To open the application, run `ddev describe`; the API base URL is `https://digital-wallet.ddev.site` with this project's default DDEV name.

### Native PHP and SQLite alternative

Use this option if PHP 8.4.1+ and Composer are installed locally. The `.env.example` defaults to SQLite and the database queue:

```bash
composer install
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The native API base URL is `http://127.0.0.1:8000`. For this setup, use `composer` and `php artisan` in place of the `ddev composer` and `ddev artisan` commands below.

## Quality checks

Run the project quality script from the repository root:

```bash
ddev composer quality
```

For the native setup, run `composer quality` instead. The script runs Pint in test mode, PHPStan, and the test suite.

## Try the API with Apidog

Import the collection at the repository root, [`Digital Wallet Coding Challenge.apidog.json`](./Digital%20Wallet%20Coding%20Challenge.apidog.json), into Apidog. Set the collection/environment base URL to match your setup, then send the included requests:

| Purpose | Method and path |
| --- | --- |
| Generate payment XML | `POST /api/v1/generateXML` |
| Receive Acme Bank webhook(s) | `POST /api/v1/webhooks/AcmeBank` |
| Receive PayTech Bank webhook(s) | `POST /api/v1/webhooks/PayTechBank` |

For DDEV, set the base URL to `https://digital-wallet.ddev.site`; for native PHP, use `http://127.0.0.1:8000`.

The webhook endpoints accept JSON with a `webhooks` array of strings. The collection includes sample request bodies. For example, an Acme request looks like:

```json
{
  "webhooks": [
    "156,52//202506159000003//20250617"
  ]
}
```

The XML endpoint accepts the nested `PaymentRequestMessage` JSON object shown in the Apidog collection and returns XML. Optional XML fields are omitted for the special values implemented by the service: `Notes` when absent, `PaymentType` when it is `"99"`, and `ChargeDetails` when it is `"SHA"`.

### Observe webhook processing

In a second terminal, start the database queue worker:

```bash
ddev artisan queue:work database --sleep=1 --tries=5
```

For native PHP, run `php artisan queue:work database --sleep=1 --tries=5`. With the worker running, send a webhook request from Apidog. The request handler stores a row in `webhooks` and enqueues a `ProcessWebhook` job in `jobs`. The worker processes the job; completed queue rows are removed from `jobs`.

To inspect the DDEV database, open a database shell with `ddev mysql` and run:

```sql
SELECT id, date, amount, reference, note, internal_reference, status, processed_at
FROM webhooks;

SELECT id, queue, attempts
FROM jobs;

SELECT id, type, notifiable_id, data, created_at
FROM notifications;

