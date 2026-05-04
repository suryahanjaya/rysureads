# Lab 3 — Fulfillment Proof
**Project:** RysuReads — Online Bookstore  


---

## Task 1 — Create Database ✅

Database `rysureads` is created in `config/init.sql`:

```sql
CREATE DATABASE IF NOT EXISTS rysureads
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## Task 2 — Create Core Tables ✅

Three tables are defined in `config/init.sql`:

```sql
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(150) NOT NULL,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150)  NOT NULL,
    price       DECIMAL(10,2) NOT NULL,
    category_id INT,
    description TEXT,
    image       VARCHAR(255)  DEFAULT NULL,
    created_at  DATETIME      DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

Six default categories are seeded via `INSERT IGNORE INTO categories`.

> Source: `config/init.sql`

---

## Task 3 — Create Database Connection ✅

`config/database.php` holds the single shared connection used by every page:

```php
$conn = new mysqli("localhost", "root", "", "rysureads");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
```

Pages require it with a single line:

```php
require_once '../config/database.php';
```

> Source: `config/database.php`

---

## Task 4 — Create Item Submission Form ✅

`pages/create_item.php` renders a form with four fields. The category dropdown is populated dynamically from the database:

```php
$cats = $conn->query("SELECT id, name FROM categories ORDER BY name");
```

```html
<form action="save_item.php" method="POST">
    <input  type="text"   name="name"        placeholder="e.g. The Great Gatsby" required>
    <input  type="number" name="price"        placeholder="e.g. 12.99" step="0.01" required>
    <select name="category_id" required>
        <?php while ($cat = $cats->fetch_assoc()): ?>
            <option value="<?php echo $cat['id']; ?>">
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
        <?php endwhile; ?>
    </select>
    <textarea name="description" rows="4" required></textarea>
    <button type="submit" class="btn-submit">Save Book</button>
</form>
```

> Source: `pages/create_item.php`

---

## Task 5 — Insert Data into Database ✅

`pages/save_item.php` processes the POST and inserts using a prepared statement:

```php
$stmt = $conn->prepare(
    "INSERT INTO items (name, price, category_id, description) VALUES (?, ?, ?, ?)"
);
$stmt->bind_param("sdis", $name, $price, $category_id, $description);
$stmt->execute();
```

On success it redirects to `items.php`; on failure it redirects back with an error message.

> Source: `pages/save_item.php`

---

## Task 6 — Display Items from Database ✅

`pages/items.php` retrieves all items joined with their category name and renders them as Bootstrap cards (same CSS classes already used in `index.php`):

```php
$sql    = "SELECT items.*, categories.name AS category_name
           FROM items
           LEFT JOIN categories ON items.category_id = categories.id
           ORDER BY items.created_at DESC";
$result = $conn->query($sql);
```

```php
<?php while ($item = $result->fetch_assoc()): ?>
    <div class="col-md-4 mb-4 book-card-wrapper">
        <div class="card book-card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                <div class="book-price">$<?php echo number_format($item['price'], 2); ?></div>
                <a href="item_details.php?id=<?php echo $item['id']; ?>" class="btn-view">View Details</a>
            </div>
        </div>
    </div>
<?php endwhile; ?>
```

> Source: `pages/items.php`

---

## Reusability Notes

| Reused Asset | Used In |
|---|---|
| `components/header.php` | `page_open.php` → every new page |
| `components/footer.php` | `page_close.php` → every new page |
| `public/css/style.css` | `page_open.php` → every new page |
| `public/js/script.js` | `page_close.php` → every new page |
| `.book-card`, `.btn-view`, `.book-price` CSS | `items.php` |
| `.contact-section`, `.btn-submit` CSS | `create_item.php` |
| `config/database.php` | `items.php`, `create_item.php`, `save_item.php` |

New components `components/page_open.php` and `components/page_close.php` centralise the `<head>`, Bootstrap CDN, header, footer, and JS inclusion so there is zero duplication across new pages.

---

## Summary

| Task | Requirement | Status |
|------|-------------|--------|
| 1 | Create `rysureads` | ✅ Done |
| 2 | Tables: `users`, `categories`, `items` | ✅ Done |
| 3 | `config/database.php` with mysqli connection | ✅ Done |
| 4 | `pages/create_item.php` — item form | ✅ Done |
| 5 | `pages/save_item.php` — INSERT with prepared statement | ✅ Done |
| 6 | `pages/items.php` — SELECT + card display | ✅ Done |

All **6 tasks** of Lab 3 are fully implemented in the **RysuReads** project.
