# TrustFactory Cart

This is a Laravel + Inertia.js project for a simple e-commerce application with persistent cart functionality.

## 🚀 Installation & Running Locally

Follow these steps to set up and run the project:

### 1. Clone the repository

```bash
git clone https://github.com/Maxo1991/trustfactory-cart.git
cd trustfactory-cart
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node.js dependencies

```bash
npm install
```

### 4. Run the Laravel development server

```bash
php artisan serve
```

> The application will be available at `http://127.0.0.1:8000` by default.

### 5. Run Vite for frontend

```bash
npm run dev
```

> This allows Vue.js components to automatically reload during development.

### 6. Create environment file

```bash
cp .env.example .env
```
> Before continuing, you must configure the .env file.

### 7. Create database and seed the database (create users and products)

```bash
php artisan migrate
php artisan db:seed
```

This will create:

* **Admin User** (`admin@example.com` / `password123`)
* **Customer User** (`customer@example.com` / `password123`)
* **Three products**: `Product One`, `Product Two`, `Product Tree`

---

## 🔑 Login Credentials

* **Admin:** `admin@example.com` / `password123`
* **Customer:** `customer@example.com` / `password123`

---

## ⚡ Notes

* Make sure your `.env` file has the correct database configuration (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) before running the seeder.
* Cart backups are automatically saved in the `cart_backups` table and will be restored after logout/login.

---

## 🛠 Next Steps

* Test adding products to the cart.
* Test restoring the cart after login.
* Test creating an order and automatically clearing the cart backup.
