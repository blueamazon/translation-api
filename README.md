# Laravel Translation Management API

A high-performance, scalable translation service built with Laravel. Designed to manage multi-language content with tagging, search, and real-time JSON export for frontend applications like Vue.js.

Perfect for teams needing a secure, fast, and maintainable translation backend with support for 100k+ records.

---

## 🚀 Features

- ✅ **Multi-locale support** (`en`, `fr`, `es`, extensible)
- ✅ **Tagging system** (`web`, `mobile`, `desktop`, etc.)
- ✅ **RESTful API** for CRUD operations
- ✅ **Search** by key, content, or tags
- ✅ **JSON export endpoint** – always returns updated data
- ✅ **Token-based authentication** (Laravel Sanctum)
- ✅ **Dockerized** with Nginx, MySQL, PHP-FPM, and phpMyAdmin
- ✅ **OpenAPI 3.0 documentation** with Swagger UI
- ✅ **100k+ records** seeded via optimized factory
- ✅ **Fast export** (<500ms) using streamed JSON
- ✅ **PSR-12 & SOLID** compliant
- ✅ **No external CRUD or translation libraries**
- ✅ **Test coverage ready** (unit & feature)

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 11+](https://laravel.com)
- **Database**: MySQL 8.0
- **Containerization**: Docker + Docker Compose
- **API Docs**: OpenAPI 3.0 + Swagger UI (static)
- **Auth**: Laravel Sanctum (token-based)
- **Frontend Ready**: JSON export for Vue.js, React, etc.
- **CDN Support**: Streamed responses ready for edge caching

---

## 📦 Prerequisites

Before you begin, ensure you have:
- [Docker](https://www.docker.com/) and `docker-compose`
- Git
- A terminal (PowerShell, CMD, or Bash)
- Optional: Postman or a browser for testing

---

## 🚀 Step-by-Step Setup

Follow these steps to get the API up and running locally.

### Step 1: Clone the Repository
```bash
git clone https://github.com/your-username/translation-api.git
cd translation-api
```

### Step 2: Start Docker Containers
```bash
docker-compose up -d --build
```

✅ This will:
- Build the PHP container  
- Start MySQL on port `3306`  
- Serve Laravel via Nginx on port `8080`  
- Include phpMyAdmin on port `8081`  

### Step 3: Install Laravel Dependencies
```bash
docker-compose exec app composer install
```

### Step 4: Generate Application Key
```bash
docker-compose exec app php artisan key:generate
```

### Step 5: Copy Environment File
```bash
cp .env.example .env
```

### Step 6: Run Migrations and Seed Data
```bash
docker-compose exec app php artisan migrate --seed
```

### Step 7: View API Documentation
[http://localhost:8080/docs/index.html](http://localhost:8080/docs/index.html)

### Step 8: Access Services
- **Laravel App** → [http://localhost:8080](http://localhost:8080)  
- **phpMyAdmin** → [http://localhost:8081](http://localhost:8081)  

---
