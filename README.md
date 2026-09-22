# PHP-SBCS / PHP Session Based Cart System

A small, no-database PHP shopping cart demo built with sessions, CSRF-protected POST actions, Bootstrap 5 UI, and optional JSON order storage.

[Live Demo](https://www.anbarli.com.tr/PHP-SBCS/) · [Report a Bug](https://github.com/anbarli/PHP-SBCS/issues/new?template=bug_report.md) · [Request a Feature](https://github.com/anbarli/PHP-SBCS/issues/new?template=feature_request.md) · [Turkce README](README.tr.md)

![License](https://img.shields.io/badge/license-MIT-green)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4)
![Status](https://img.shields.io/badge/status-learning%20demo-blue)

## Why This Exists

Many PHP shopping cart examples are either too old, tied to a database, or skip basic security patterns. PHP-SBCS keeps the surface area small so beginners can study the cart flow while still seeing practical safeguards such as POST-only cart mutations and CSRF tokens.

Use this project when you want:

- A simple PHP session shopping cart example
- A no-database cart and checkout demo
- A Bootstrap 5 storefront starter for learning
- JSON order persistence that is easy to inspect
- A compact project for practicing PHP form handling and session management

> This repository is designed for learning and demos. It is not a drop-in production ecommerce platform.

## Quick Start

```bash
git clone https://github.com/anbarli/PHP-SBCS.git
cd PHP-SBCS
php -S localhost:8000
```

Open:

```text
http://localhost:8000
```

## Features

- Session-based cart management
- Add, remove, and clear cart actions
- CSRF protection for POST actions
- Server-side product catalog for trusted pricing
- Checkout form with name, email, phone, address, and payment method
- Optional JSON order storage under `orders/YYYY/MM/DD/ORDERNO.json`
- 8-character order number generation
- Bootstrap 5 responsive interface
- Apache `.htaccess` rule for blocking direct access to order files

## Requirements

- PHP 7.4 or newer
- Apache, PHP built-in server, or another PHP-capable web server
- A web browser

No database, Composer install, build step, or framework is required.

## Usage

1. Select a quantity on a product card.
2. Click `Add to Cart`.
3. Review cart totals.
4. Remove items or clear the cart when needed.
5. Fill the checkout form and submit the order.

Demo mode is enabled in `index.php`, so submitted orders are simulated and not written to disk on the public demo.

## Configuration

The project is intentionally small. The main settings are currently defined near the top of `index.php`:

```php
$demo_mode = true;
$currency = "$";
```

For local storage tests, set demo mode to `false`. Orders will be saved as JSON files in the date-partitioned `orders/` directory.

## Project Structure

```text
/
  index.php      Application logic and UI
  .htaccess      Basic Apache access rules
  LICENSE        MIT license
  README.md      English documentation
  README.tr.md   Turkish documentation
```

When order persistence is enabled, the app creates this runtime path:

```text
orders/YYYY/MM/DD/ORDERNO.json
```

## Security Notes

PHP-SBCS includes several basic safeguards for a learning project:

- Session cookies use `HttpOnly`, `SameSite=Lax`, and secure cookies when served over HTTPS.
- Cart mutations use POST requests with CSRF token checks.
- Product prices are read from the server-side product catalog.
- Basic security headers are sent from `index.php`.
- `orders/` access is blocked for Apache through `.htaccess`.

Production usage needs more work:

- Store orders outside the web root or in a managed database.
- Add authentication and authorization for order access.
- Encrypt or otherwise protect personal data.
- Add rate limiting, audit logs, and monitoring.
- Configure equivalent access rules for Nginx, Caddy, or other servers.
- Disable verbose error reporting in production.

See [SECURITY.md](SECURITY.md) for supported reporting guidance.

## Roadmap

- Add screenshots or a short demo GIF to the README
- Add a small `config.php` file for demo mode and currency settings
- Move cart and order helpers into separate files
- Add PHPUnit tests for cart operations and order number generation
- Add optional Docker support
- Add example deployment notes for Apache and Nginx

## Contributing

Contributions are welcome, especially beginner-friendly improvements. Good first issues include documentation fixes, UI screenshots, small refactors, test coverage, and examples.

Read [CONTRIBUTING.md](CONTRIBUTING.md) before opening a pull request.

## Recommended GitHub Metadata

Suggested About description:

```text
Simple PHP session shopping cart demo with Bootstrap 5, CSRF protection, checkout form, and optional JSON order storage. No database required.
```

Suggested topics:

```text
php, shopping-cart, php-shopping-cart, session-cart, php-session, ecommerce,
e-commerce, checkout, cart-system, bootstrap-5, vanilla-php, no-database,
csrf-protection, json-storage, php-demo, beginner-friendly, learning-project,
web-development, order-management, php-project
```

## License

MIT License. See [LICENSE](LICENSE) for details.
