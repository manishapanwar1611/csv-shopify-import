# CSV to Shopify Product Import System

This project is a **Laravel 12 application** designed to upload product CSV files, process them asynchronously, and import products into Shopify using the Shopify Admin API. The application also supports user authentication using **Laravel Breeze**.

---

##  Features

* User registration & login (Laravel Breeze)
* CSV upload for products
* Queue-based product processing
* Shopify API integration
* Import status tracking
* Error logging 
* Clean and modular architecture (Jobs, Services, Controllers)

---

##  Requirements

* PHP 8.2+
* Laravel 12
* Composer
* MySQL 5.7+/MariaDB
* Shopify API Credentials

---

##  Installation Steps

### 1. Clone Repository

```bash
git clone <your-repo-url>
cd project-folder
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment File

Copy `.env.example` → `.env`

```bash
cp .env.example .env
```

Set database credentials:

```
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
```

Set Shopify credentials:

```

SHOPIFY_STORE_DOMAIN=your-store.myshopify.com
SHOPIFY_API_VERSION=xxxx
SHOPIFY_ACCESS_TOKEN=shpat_xxxxx
SHOPIFY_COLLECTION_ID=xxxx
```

Set queue driver:

```
QUEUE_CONNECTION=
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Install Laravel Breeze

(Project already created with Breeze)

```bash
php artisan breeze:install
npm install && npm run build
php artisan migrate
```

### 6. Start Local Server

```bash
php artisan serve
```

Start queue worker:

```bash
php artisan queue:work
```


---

## Register User and login

* Register user
* Login User


##  CSV Upload Process

### 1. User uploads CSV file

* File validated (CSV only)
* Import record created
* Job dispatched to queue

### 2. Background Job (ProcessCsvJob)

* Read CSV line by line
* Validate product fields
* Send product data to Shopify API
* Update import status


---

## Shopify API Integration

Using Shopify Admin REST API:

* Create Product
* Add Images

File: `app/Services/ShopifyService.php`

---

---



## Authentication (Laravel Breeze)

This project uses Breeze for authentication.

* Register
* Login
* Logout
* Password reset

Routes are available in:

```
routes/auth.php
routes/web.php
```

---


##  Author

Developed by Manisha Panwar
