# Laravel ERP with Sanctum-Based Shared Login

![image](https://github.com/user-attachments/assets/5504aa94-ecdb-4a18-98c3-43c5b761c57f)

A single Laravel application (**ecommerce-app**) serving dual roles:
- 🛒 A functional **ERP system** with products, sales, and reporting
- 🔑 A **shared login provider** for seamless authentication into `foodpanda-app`

---

## 🚀 Live Demo Links
| App            | Link                                              | Test Credentials                                  |
|----------------|---------------------------------------------------|--------------------------------------------------|
| Ecommerce App  | [Ecommerce App](https://sf-ecommerce-app-main-nxuy9j.laravel.cloud/) | Email: admin@gmail.com  <br> Pass: 12345678         |
| Foodpanda App  | [Foodpanda App](https://sf-foodpanda-app-main-ivwd7d.laravel.cloud/) | Same (auto-login tested)                          |

---

## 📌 Project Purpose

This project demonstrates:
- A robust mini ERP with accounting logic and date-based reporting
- A secure Laravel Sanctum implementation for shared login into another app (`foodpanda-app`)
- Coordinated logout handling across systems

---

## ⚙️ Setup Instructions

### 🔧 ecommerce-app (ERP + Auth Provider)

```bash
cd ecommerce-app
cp .env.example .env
composer install
php artisan key:generate
# Set DB credentials + APP_URL
php artisan migrate --seed
php artisan serve
```

#### `.env` snippet:
```dotenv
APP_URL=http://sf-ecommerce-app.test
FOODPANDA_APP_URL=http://sf-foodpanda-app.test
```

---

### 🔧 foodpanda-app (Auth Consumer)

```bash
cd foodpanda-app
cp .env.example .env
composer install
php artisan key:generate
# Set DB credentials + APP_URL + ecommerce DB access
php artisan migrate
php artisan serve
```

#### `.env` snippet:
```dotenv
APP_URL=http://sf-foodpanda-app.test
ECOMMERCE_APP_URL=http://sf-ecommerce-app.test
# Optional: if using remote DB connection
ECOMMERCE_DB_*=...
```

---

## 🔁 Multi-System Login Flow

1. User logs into `ecommerce-app`
2. Clicks "Access Foodpanda App" — redirected with a one-time Sanctum token
3. `foodpanda-app` reads + validates the token
4. User is auto-logged in based on matched email

🧩 Shared logout is coordinated through `/shared-logout` endpoints on both apps.

---

## 📦 ERP Features (in `ecommerce-app`)

- ✅ Product CRUD with stock tracking
- ✅ Sale form with VAT, discount, payment inputs
- ✅ Automatic journal entries for:
  - `sales`, `discount`, `vat`, `cash`, and `due`
- ✅ Filterable financial report by date
- ✅ Minimal UI with Tailwind + Alpine.js

---

## 🖼️ Screenshots

(Insert the screenshots you already shared in your earlier message.)

---

## ✅ Submission Checklist

- [x] Laravel app with ERP + auth logic (`ecommerce-app`)
- [x] Paired Laravel app (`foodpanda-app`) with shared login support
- [x] Deployed or zipped with credentials
- [x] README clarity + working demo flow


## Screenshots

![image](https://github.com/user-attachments/assets/34c044d3-e8ce-4b60-bfcf-6a230360f3fd)

![image](https://github.com/user-attachments/assets/0e4e3ce9-6360-4e5b-815c-18b365d556a4)

![image](https://github.com/user-attachments/assets/15d7fe7b-093e-49a2-98e4-9ada5e0a7266)

![image](https://github.com/user-attachments/assets/b164b091-482d-482a-ba28-ce8e3cb06e10)



🚀 Live Demo Links
App	Link	Test Credentials
Ecommerce App	https://ecommerce-app.onrender.com	Email: test@demo.com <br> Pass: password
Foodpanda App	https://foodpanda-app.onrender.com	Same (auto-login tested)
ERP App	https://erp-app.onrender.com	Email: admin@erp.com <br> Pass: password