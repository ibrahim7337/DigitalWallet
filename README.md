# Digital Wallet

A Laravel API project for generating bank payment-request XML and processing transaction webhooks from Acme Bank and PayTech Bank.

## Requirements

* Git
* Docker and [DDEV](https://ddev.com/)
* [Apidog](https://apidog.com/) (optional)

## Installation

```bash
git clone https://github.com/ibrahim7337/DigitalWallet.git
cd DigitalWallet

ddev config --project-name=digital-wallet-test
ddev start

cp .env.example .env
```

Configure `.env`:

```dotenv
APP_URL=https://digital-wallet-test.ddev.site

DB_CONNECTION=mariadb
DB_HOST=db
DB_PORT=3306
DB_DATABASE=db
DB_USERNAME=db
DB_PASSWORD=db

QUEUE_CONNECTION=database
```

Then run:

```bash
ddev composer install
ddev artisan key:generate
ddev artisan migrate --seed
ddev composer quality
```

Check the application URL:

```bash
ddev describe
```

## API Endpoints

| Method | Endpoint                       | Description                   |
| ------ | ------------------------------ | ----------------------------- |
| `POST` | `/api/v1/generateXML`          | Generate payment-request XML  |
| `POST` | `/api/v1/webhooks/AcmeBank`    | Receive Acme Bank webhooks    |
| `POST` | `/api/v1/webhooks/PayTechBank` | Receive PayTech Bank webhooks |

## Run Queue Worker

Open a second terminal and run:

```bash
ddev artisan queue:work database --sleep=1 --tries=5
```

## API Testing

Import [`Digital Wallet Coding Challenge.apidog.json`](./Digital%20Wallet%20Coding%20Challenge.apidog.json) into Apidog and configure the base URL using the URL shown by `ddev describe`.
