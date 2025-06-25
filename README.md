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

## 3. How to Use Postman to Check the Task

**Goal:** Generate a token from `ecommerce-app` and get an auto-login URL for `foodpanda-app`.

1.  **Ensure Prerequisites:**
    *   Both Laravel apps are running.
    *   You have a test user in `ecommerce-app` and a corresponding user (same email) in `foodpanda-app`.
2.  **Clear Postman Cookies for `ecommerce-app` Domain:** (e.g., `sf-ecommerce-app.test`)
    *   In Postman: Cookies (under Send button) > Manage Cookies > Remove existing for the domain.
3.  **Step A: (Postman) GET Login Page from `ecommerce-app` (to get CSRF Token)**
    *   **Method:** `GET`
    *   **URL:** `http://sf-ecommerce-app.test/login` (Use your actual URL)
    *   **Headers:** `Accept: text/html`
    *   **Action:** Send. From the HTML response body, find and copy the `_token` value (CSRF token) from the hidden input field.
4.  **Step B: (Postman) POST to Login Route of `ecommerce-app` (to establish session)**
    *   **Method:** `POST`
    *   **URL:** `http://sf-ecommerce-app.test/login`
    *   **Headers:**
        *   `Accept: application/json`
        *   `Content-Type: application/x-www-form-urlencoded`
    *   **Body (`x-www-form-urlencoded` tab):**
        *   `_token`: [CSRF token from Step A]
        *   `email`: your_test_user@example.com
        *   `password`: your_password
    *   **Action:** Send. Verify successful login (Postman should now have the session cookie for `sf-ecommerce-app.test`).
5.  **Step C: (Postman) POST to Generate Shared Login Token**
    *   **Method:** `POST`
    *   **URL:** `http://sf-ecommerce-app.test/api/shared-login/generate-token`
    *   **Headers:**
        *   `Accept: application/json`
        *   `X-Requested-With: XMLHttpRequest`
        *   **Important:** DO NOT manually add a `Cookie` header. Postman will use the session cookie from Step B.
    *   **Body:** None.
    *   **Action:** Send.
    *   **Expected JSON Response:**
        ```json
        {
            "message": "Token generated successfully.",
            "access_token": "SOME_TOKEN_STRING",
            "token_type": "Bearer",
            "auto_login_url": "http://sf-foodpanda-app.test/auto-login?token=SOME_TOKEN_STRING",
            "user": { "id": ..., "name": "...", "email": "..." }
        }
        ```
6.  **Test Auto-Login:**
    *   Copy the `auto_login_url` from the Postman response.
    *   Paste it into your web browser.
    *   You should be redirected to `foodpanda-app` and logged in as the test user.
    *   The token is one-time use; trying the URL again should fail.

**Note on Authentication Bypass:** If you temporarily disabled authentication in `ecommerce-app`'s `SharedLoginController` or its route for testing, ensure it's re-enabled for proper functionality.
