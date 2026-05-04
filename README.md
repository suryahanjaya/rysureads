# RysuReads

RysuReads is a responsive PHP-based online bookstore and catalog application for browsing, searching, and managing book/item listings. It includes multilingual UI support, theme switching, authentication, admin workflows, and server-side catalog routing.

The project uses a front-controller entry point, a MySQL-compatible database layer, shared page components, and a custom responsive UI built on top of Bootstrap and handcrafted CSS.

## Table of Contents

1. [Project Overview](#project-overview)
2. [Key Features](#key-features)
3. [Tech Stack](#tech-stack)
4. [Project Structure](#project-structure)
5. [Data Model](#data-model)
6. [Prerequisites](#prerequisites)
7. [Installation](#installation)
8. [Database Setup](#database-setup)
9. [Configuration](#configuration)
10. [How to Run](#how-to-run)
11. [Available Routes](#available-routes)
12. [Default Demo Account](#default-demo-account)
13. [How the App Works](#how-the-app-works)
14. [Development Notes](#development-notes)
15. [Troubleshooting](#troubleshooting)
16. [License](#license)

## Project Overview

RysuReads is designed as an online reading catalog where users can browse a curated collection of items, search by name or category, open item detail pages with store availability, create accounts, log in, buy items, and view purchased items in a personal library page.

The app is intentionally structured without a build step. Pages are rendered with PHP, styled with Bootstrap 5 and custom CSS, and enhanced with vanilla JavaScript.

## Key Features

- Responsive desktop and mobile header
- Desktop dropdown-style navigation panels
- Mobile hamburger drawer navigation
- Theme toggle with persistent preference
- Language toggle with persistent preference
- Live AJAX search results
- Search result cards with language-aware descriptions
- Category browsing and server-side sorting
- Item detail pages with store location data
- User authentication: register, login, logout, forgot password, reset password
- Admin workflows: create item, edit item, update item, admin dashboard
- Purchase tracking and personal "My Books" page
- SEO-friendly metadata, sitemap, and robots file

## Tech Stack

| Area | Stack |
| --- | --- |
| Backend | PHP 8.0+ compatible syntax, session-based authentication, custom mysqli-compatible database wrapper backed by PDO when native `mysqli` is unavailable |
| Database | MySQL or MariaDB |
| Frontend | HTML5, vanilla JavaScript, custom CSS, Bootstrap 5.3.0 |
| Typography | Google Fonts: Cormorant Garamond, Inter |
| Data and Utilities | UTF-8 / utf8mb4 support, prepared statements, local storage for theme and language preferences, server-side routing through `public/index.php` |

## Project Structure

```text
rysureads/
|-- components/
|   |-- footer.php
|   |-- header.php
|   |-- mobile_drawer.php
|   |-- page_close.php
|   `-- page_open.php
|-- config/
|   |-- app.php
|   |-- database.php
|   `-- init.sql
|-- docs/
|   |-- er_diagram.mmd
|   `-- project_report_outline.md
|-- pages/
|   |-- admin.php
|   |-- buy_item.php
|   |-- contact.php
|   |-- create_item.php
|   |-- edit_item.php
|   |-- forgot_password.php
|   |-- items.php
|   |-- item_details.php
|   |-- login.php
|   |-- logout.php
|   |-- my_books.php
|   |-- register.php
|   |-- reset_password.php
|   |-- save_item.php
|   |-- search.php
|   |-- search_items.php
|   `-- update_item.php
|-- public/
|   |-- css/style.css
|   |-- image.php
|   |-- images/
|   |-- index.php
|   |-- js/script.js
|   |-- robots.txt
|   `-- sitemap.xml
|-- lab2_proof.md
|-- lab3_proof.md
`-- README.md
```

## Data Model

The application centers around these tables:

- `users`: user account data, roles, password reset token and expiry fields
- `categories`: item grouping and URL slugs
- `items`: item metadata, slug, price, rating, English and primary descriptions, image path
- `item_locations`: store availability, Google Maps links, availability notes
- `purchases`: tracked purchases per user and item

The schema is defined in `config/init.sql`, and the application also has runtime schema bootstrap logic in `config/database.php` so it can create missing tables or columns when the database is empty or partially migrated.

## Prerequisites

- PHP 8.0 or newer
- MySQL or MariaDB
- Web server with PHP support
- `pdo_mysql` extension enabled
- A modern browser with JavaScript enabled

## Installation

1. Place the project folder in your local web workspace or clone it from the repository.
2. Confirm PHP and MySQL are available:

```bash
php -v
mysql --version
```

3. Open `config/database.php` and update the connection values if your local setup differs from the defaults:

```php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'rysureads';
```

4. Create the `rysureads` database in MySQL.
5. Import `config/init.sql` manually or let the application bootstrap the schema automatically when it first connects.

The app seeds sample categories, sample items, item locations, and a demo admin account.

## Database Setup

### Manual import

Use your MySQL client or phpMyAdmin to import `config/init.sql` into the `rysureads` database.

If you use the MySQL CLI:

```sql
SOURCE config/init.sql;
```

If you use phpMyAdmin:

1. Select the `rysureads` database.
2. Open the Import tab.
3. Choose `config/init.sql`.
4. Run the import.

### Automatic bootstrap

`config/database.php` contains runtime schema creation and seed logic. This is useful if the database exists but tables are missing, if you are setting up a fresh local environment, or if you want the sample data inserted automatically.

The bootstrap creates or updates `categories`, `items`, `item_locations`, `users`, and `purchases`, and adds missing columns such as `items.slug`, `items.rating`, `items.description_en`, `users.role`, `users.reset_token`, and `users.reset_expires_at`.

## Configuration

### Database credentials

Update `config/database.php` if your MySQL host, username, password, or database name is different from the defaults.

### Base URL

The app uses route-style paths such as `/products`, `/search`, and `/contact`. If you deploy it in a subfolder or on a server without friendly URL support, make sure the document root and rewrite/front-controller behavior point to `public/index.php`.

### Assets

Item images are served through `public/image.php` and helper functions in `config/app.php`, so image paths are resolved safely from the `public/images/` directory.

## How to Run

### Recommended local setup

Use a PHP-capable web server and point the document root to the `public/` directory.

Example with the PHP built-in server:

```bash
php -S 127.0.0.1:8000 -t public
```

Then open:

```text
http://127.0.0.1:8000
```

### Apache, XAMPP, or Laragon

If you use Apache, point the site document root to `rysureads/public`.

If your server does not support clean URLs automatically, add rewrite or front-controller support so URLs like `/products` and `/search` resolve through `public/index.php`.

## Available Routes

The front controller in `public/index.php` maps the main pages and actions:

- `/` - home page
- `/products` - catalog listing
- `/products/category/{slug}` - category-filtered catalog
- `/products/{slug}` - item detail page
- `/search` - search page
- `/search-items` - AJAX search endpoint
- `/contact` - contact page
- `/login` - login form
- `/register` - registration form
- `/forgot-password` - password reset request
- `/reset-password` - password reset form
- `/logout` - logout action
- `/create-item` - admin create page
- `/save-item` - create-item submission
- `/edit-item` - admin edit page
- `/update-item` - edit submission
- `/admin` - admin dashboard
- `/buy-item` - purchase action
- `/my-books` - personal library page

## Default Demo Account

The project seeds a demo admin account:

- Email: `admin@rysureads.local`
- Password: `admin12345`

Change this immediately in any shared or public environment.

## How the App Works

### Shared layout

The app uses a shared page wrapper with `components/page_open.php` and `components/page_close.php`. This wrapper centralizes the document head, Bootstrap CSS, global header, footer, mobile drawer, and JavaScript bundle.

### Header behavior

Desktop navigation opens panel-style dropdown boxes. Mobile navigation opens a full-screen drawer. Theme and language toggles are available in both layouts, and the preference is persisted in localStorage.

### Language switching

The selected language is stored in `rysureads-lang`. The interface supports English and Chinese. The text swap is handled through `data-i18n` and `data-i18n-placeholder` attributes in the DOM.

### Theme switching

The theme mode is stored in `rysureads-theme`. Supported values are `light` and `dark`.

### Search

The search page uses AJAX to fetch results from `/search-items?q=...` without reloading the page. Search works against item names, descriptions, and category names.

### Item descriptions

Item cards and search results include language-aware description rendering. English preview text is shown in `.item-desc-en`, while Chinese preview text is shown in `.item-desc-zh`.

### Admin and user flows

The project includes protected flows for admin-only create and edit actions, user login and registration, password reset, and purchase tracking.

## Development Notes

- The project does not use npm, Vite, or a separate frontend build pipeline.
- CSS and JavaScript are served directly from the repo.
- Bootstrap is loaded from CDN.
- Google Fonts are loaded from CDN.
- Database queries use prepared statements where user input is involved.
- The app expects UTF-8 data.

## Troubleshooting

### Database connection failed

Check that the MySQL service is running, the credentials in `config/database.php` are correct, the database name is correct, and the `pdo_mysql` extension is enabled.

### Friendly routes do not work

If `/products` or `/search` returns a 404, make sure the server document root points to `public/` and that the server supports rewrite or front-controller routing.

### Images do not load

Verify that the file exists in `public/images/`, the database path is correct, and `public/image.php` is reachable.

### Search shows the wrong language

Confirm that `data-lang` is switching correctly on the `<html>` element, localStorage is not locked to an old value, and the page includes both `.item-desc-en` and `.item-desc-zh` spans.

### Admin page access denied

Make sure you are logged in with an account that has `role = 'admin'`.

## License

No explicit license file is included in this repository. Add one if you intend to distribute or publish the project.
