# Royal Shine

Royal Shine is a Laravel jewellery catalogue with a public storefront and a Filament administration panel. Customers can browse published products, search and filter the catalogue, and send product or general enquiries. It does not include cart, checkout, or payment processing.

## Features

- Jewellery categories, collections, product galleries, banners, and content pages managed in the admin panel.
- Public home, catalogue, category, product, collection, gallery, about, contact, policy, and testimonial pages.
- Search and catalogue filters, sitemap, canonical metadata, and product structured data.
- Product and contact enquiry forms with validation and spam controls.
- Admin roles for super admins, admins, content managers, and enquiry managers.
- Enquiry assignment, customer enquiry history, database notifications, and admin activity logs.
- Dashboard statistics and charts.

## Requirements

- PHP 8.2 or newer and Composer.
- Node.js and npm for compiling frontend assets.
- MySQL/MariaDB for deployment, or SQLite for local development.

## Local setup

From the project directory in PowerShell:

```powershell
composer install
npm ci
Copy-Item .env.example .env
php artisan key:generate
```

Configure `.env` with the database connection and a one-time initial admin account:

```dotenv
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=royal_shine
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

ADMIN_NAME="Royal Shine Admin"
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=use-a-long-unique-password
```

Create the database before migrating, then run:

```powershell
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Open the application at `http://127.0.0.1:8000` and the admin panel at `http://127.0.0.1:8000/admin` (or the path configured by `ADMIN_PATH`).

The seeder creates an initial super-admin only when both `ADMIN_EMAIL` and `ADMIN_PASSWORD` are set. It uses `firstOrCreate`, so re-running the seeder does not replace an existing user's password or role. Remove the bootstrap password from `.env` after the initial seed and clear cached configuration with `php artisan config:clear`. Do not commit `.env` or use example credentials in production.

To run the development server and Vite together, use `composer run dev`.

## Admin roles

| Role | Access |
| --- | --- |
| `super_admin` | All admin modules, including user and activity-log management |
| `admin` | Catalogue, content, enquiries, customers, and settings |
| `content_manager` | Catalogue and public-content modules |
| `enquiry_manager` | Enquiries and customer enquiry history |

Admin accounts can be managed from the Users area by an authorized administrator. Configure SMTP in `.env` if password-reset email is required. Public enquiries are saved in the admin panel; email delivery depends on the configured mail transport.

## Tests and production assets

Run the automated test suite and production asset build with:

```powershell
php artisan test
npm run build
```

The PHPUnit configuration uses an in-memory SQLite database so tests do not alter the configured development database.

## Shared-hosting deployment

1. Provision PHP 8.2+, Composer, MySQL/MariaDB, and the PHP extensions required by Laravel (including PDO for MySQL, Mbstring, OpenSSL, Fileinfo, Ctype, XML, and tokenizer). Set the site's PHP version and document root.
2. Deploy the application so the web server's document root points to the project's `public` directory. Do not expose the repository root, `.env`, `vendor`, or storage directories through the web server.
3. Configure the production `.env` with a unique `APP_KEY`, HTTPS `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`, database credentials, `FILESYSTEM_DISK=public`, and SMTP settings. Keep secrets out of source control.
4. Build assets before or during deployment with `npm ci` and `npm run build`; production servers do not need Node.js if the generated `public/build` assets are uploaded.
5. Install PHP dependencies with `composer install --no-dev --optimize-autoloader`, then run `php artisan migrate --force`. Back up the database before applying migrations. Do not run `migrate:fresh` on a live site.
6. Run `php artisan storage:link` and ensure `storage` and `bootstrap/cache` are writable by the PHP process. Confirm uploaded product and banner images are publicly readable.
7. Seed categories and the initial admin only when appropriate. Set `ADMIN_EMAIL` and a strong `ADMIN_PASSWORD` for the first `php artisan db:seed --force`; then remove the bootstrap password and clear cached configuration. The seeder will not overwrite an existing account.
8. Run `php artisan optimize` after environment and migration setup. Clear/rebuild Laravel caches on each deployment when routes, configuration, or views change. Use HTTPS and verify the public pages, image uploads, enquiry submission, admin login, and password reset before launch.

If the host cannot set the document root to `public`, ask the hosting provider for a secure Laravel deployment configuration rather than exposing the application root.
