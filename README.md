# 🚖 Tawseelah

**Tawseelah** is a ride-hailing and transportation management system
built with **Laravel 12** using **JWT** and **Sanctum** for API
authentication.\
The system provides APIs for both **users** and **drivers**, including
registration, login, password reset, order management, and ride
tracking.

------------------------------------------------------------------------

## 📦 Requirements

-   PHP \^8.2\
-   Composer\
-   Laravel 12\
-   MySQL or any supported database\
-   Node.js & NPM (for frontend or Vite if used)

------------------------------------------------------------------------

## ⚙️ Installation & Setup

``` bash
# Clone the project
git clone https://github.com/AbdalhalimSayed/tawseelah.git
cd tawseelah

# Install dependencies
composer install
npm install && npm run build

# Setup environment file
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start the server
php artisan serve
```

------------------------------------------------------------------------

## 🔑 Authentication

The system uses two authentication methods:\
- **Sanctum**: for simple API tokens.\
- **JWT**: for long-term sessions.

------------------------------------------------------------------------

## 👤 User Endpoints

-   `POST /api/auth/register` → Register a new user.\
-   `POST /api/auth/login` → User login.\
-   `POST /api/auth/forget-password` → Send reset link/OTP.\
-   `POST /api/auth/reset-password` → Reset user password.\
-   `GET /api/orders` → List all user orders.\
-   `POST /api/orders` → Create a new order.\
-   `GET /api/profile` → Get user profile.\
-   `PUT /api/profile` → Update user profile.

------------------------------------------------------------------------

## 🚗 Driver Endpoints

-   `POST /api/driver/register` → Register a new driver.\
-   `POST /api/driver/login` → Driver login.\
-   `GET /api/driver/orders` → View available orders.\
-   `POST /api/driver/orders/{id}/accept` → Accept an order.\
-   `POST /api/driver/orders/{id}/complete` → Complete a ride.\
-   `GET /api/driver/profile` → View driver profile.\
-   `PUT /api/driver/profile` → Update driver profile.

------------------------------------------------------------------------

## 🧪 Testing

``` bash
php artisan test
```

------------------------------------------------------------------------

## 📄 License

This project is licensed under the [MIT License](LICENSE).
