# Contributing

Thanks for helping improve PHP-SBCS. This project is intentionally small and beginner-friendly, so contributions should keep the code easy to read and easy to run.

## Good First Contributions

- Improve README wording or examples
- Add screenshots or a short demo GIF
- Add small tests for helper functions
- Improve form validation messages
- Add deployment notes for Apache, Nginx, or Caddy
- Refactor repeated cart logic without changing behavior

## Local Setup

```bash
git clone https://github.com/anbarli/PHP-SBCS.git
cd PHP-SBCS
php -S localhost:8000
```

Open `http://localhost:8000`.

## Before Opening a Pull Request

- Keep changes focused on one topic.
- Run a PHP syntax check:

```bash
php -l index.php
```

- Update documentation when behavior changes.
- Include screenshots for visible UI changes.
- Mention security impact if the change touches sessions, forms, order storage, or headers.

## Pull Request Style

Use a clear title such as:

- `docs: improve quick start`
- `security: tighten order storage notes`
- `test: add order number generation coverage`
- `ui: improve cart empty state`

## Project Scope

PHP-SBCS is a learning/demo project, not a full ecommerce platform. Large features such as payment gateway integrations, admin panels, inventory systems, and user accounts should first be discussed in an issue.
