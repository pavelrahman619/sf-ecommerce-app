# Laravel Sanctum Shared Login: ecommerce-app to foodpanda-app

## 1. Project Purpose

To implement a token-based shared login system where a user authenticated in `ecommerce-app` can seamlessly log into `foodpanda-app`. `ecommerce-app` generates a one-time Sanctum token, embedded in an auto-login URL for `foodpanda-app`.

## 2. Setup & Credentials

**General Setup (for both `ecommerce-app` and `foodpanda-app`):**
1.  Clone the repository containing both app folders.
2.  For each app:
    *   Navigate into the app's directory.
    *   Copy `.env.example` to `.env`.
    *   Run `composer install`.
    *   Run `php artisan key:generate`.
    *   Configure your primary database settings in `.env` (e.g., `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    *   Run `php artisan migrate` to create necessary tables (including `users`, `personal_access_tokens`).
    *   Ensure `APP_URL` in `.env` is set to the correct URL the app is served from (e.g., `http://sf-ecommerce-app.test`).

**Specific `.env` Variables for Shared Login:**

*   **In `ecommerce-app/.env`:**
    ```dotenv
    FOODPANDA_APP_URL=http://sf-foodpanda-app.test # URL of your foodpanda-app
    ```
*   **In `foodpanda-app/.env`:**
    ```dotenv
    ECOMMERCE_APP_URL=http://sf-ecommerce-app.test # URL of your ecommerce-app (for CORS)
    ```

**Database Strategy & Credentials for Token Validation by `foodpanda-app`:**

This implementation assumes `foodpanda-app` needs to read tokens created by `ecommerce-app`.

*   **If Using a Single Shared Database for Both Apps:**
    *   Both apps' `.env` files point `DB_DATABASE`, `DB_USERNAME`, etc., to the *same* database instance.
    *   No further DB credential setup is needed in `foodpanda-app` for token reading.
    *   `foodpanda-app` will use `Laravel\Sanctum\PersonalAccessToken` directly.

*   **If Using Separate Databases (Read-Access Shortcut Method):**
    *   `foodpanda-app` needs to connect to `ecommerce-app`'s database to read tokens.
    *   **In `foodpanda-app/.env`, add these:**
        ```dotenv
        ECOMMERCE_DB_HOST=127.0.0.1       # Host of ecommerce-app's database
        ECOMMERCE_DB_PORT=3306            # Port of ecommerce-app's database
        ECOMMERCE_DB_DATABASE=your_ecommerce_app_db_name
        ECOMMERCE_DB_USERNAME_READONLY=your_readonly_db_user_for_ecommerce
        ECOMMERCE_DB_PASSWORD_READONLY=password_for_readonly_user
        ```
    *   Ensure the `ECOMMERCE_DB_USERNAME_READONLY` user has `SELECT` permission on `ecommerce-app`'s `personal_access_tokens` and `users` tables.
    *   `foodpanda-app` should have an `App\Models\EcommerceAppToken` model that extends Sanctum's `PersonalAccessToken` and sets `protected $connection = 'ecommerce_db_connection';` (assuming `ecommerce_db_connection` is defined in `config/database.php` pointing to these `.env` vars).
    *   The `AttemptLoginViaSharedToken` middleware in `foodpanda-app` must then use `use App\Models\EcommerceAppToken as PersonalAccessToken;`.

**User Matching:** Users are matched between `ecommerce-app` and `foodpanda-app` based on **email address**.

## 3. How to Use Multi Login System

- Log into ecommerce-app using your browser.
- Navigate to the dashboard.
- Click the "Go to Foodpanda App" button.
- Observe the browser redirect to foodpanda-app and the automatic login.

## 4. Multi-App Logout Functionality

This implementation includes a coordinated logout mechanism. When a user logs out from either `ecommerce-app` or `foodpanda-app`, they will be logged out from both systems.

**Logout Flow:**

*   **If Logout is Initiated from `ecommerce-app`:**
    1.  `ecommerce-app` terminates its local user session.
    2.  The browser is redirected to `foodpanda-app`'s `/shared-logout` endpoint, passing along a `redirect_url` parameter that points back to `ecommerce-app`'s login page.
    3.  `foodpanda-app`'s `/shared-logout` endpoint terminates its local user session.
    4.  `foodpanda-app` then redirects the browser to the `redirect_url` (i.e., `ecommerce-app`'s login page).

*   **If Logout is Initiated from `foodpanda-app`:**
    1.  `foodpanda-app` terminates its local user session.
    2.  The browser is redirected to `ecommerce-app`'s `/shared-logout` endpoint.
    3.  `ecommerce-app`'s `/shared-logout` endpoint ensures its local user session is terminated (if not already).
    4.  `ecommerce-app` then redirects the browser to its own login page.

In both scenarios, the user is ultimately redirected to `ecommerce-app`'s login page after being logged out from both applications.
