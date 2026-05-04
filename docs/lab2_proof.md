# Lab 2 — Fulfillment Proof
**Project:** RysuReads — Online Bookstore  


---

## Task 1 — Project Folder Structure ✅

The required folder structure has been created under the `RysuReads/` root:

```
RysuReads/
├── public/
│   ├── index.php
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── images/
│       ├── item1.jpg … item6.jpg
│       └── logo.png
├── pages/
│   └── item_details.php
└── components/
    ├── header.php
    └── footer.php
```

All required directories (`public`, `css`, `js`, `images`, `pages`, `components`) are present.

---

## Task 2 — Homepage Layout ✅

`public/index.php` contains a valid HTML5 structure with `<DOCTYPE html>`, `<head>`, `<title>`, and `<body>` tags, including a welcome heading:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RysuReads - Online Bookstore</title>
    ...
</head>
<body>
    ...
    <h1>Welcome to RysuReads</h1>
    ...
</body>
</html>
```

> Source: `public/index.php`, lines 1–19 & 30.

---

## Task 3 — Header and Navigation Bar ✅

A reusable header component (`components/header.php`) implements a full Bootstrap navbar with logo and four navigation links: **Home**, **Items**, **Search**, and **Contact**.

```html
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #3D8D7A;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="/public/index.php">
            <img src="<?php echo $logoPath; ?>" alt="RysuReads Logo" height="36">
            RysuReads
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active" href="/public/index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/public/index.php#catalog">Items</a></li>
            <li class="nav-item"><a class="nav-link" href="/public/index.php#search-section">Search</a></li>
            <li class="nav-item"><a class="nav-link" href="/public/index.php#contact">Contact</a></li>
        </ul>
    </div>
</nav>
```

The header is included in both `index.php` and `item_details.php` via:

```php
<?php include '../components/header.php'; ?>
```

> Source: `components/header.php`, lines 8–35.

---

## Task 4 — Bootstrap Added ✅

Bootstrap 5.3.0 CDN is linked in the `<head>` section of both `index.php` and `item_details.php`:

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
```

> Source: `public/index.php`, line 15; `pages/item_details.php`, line 15.

---

## Task 5 — Item Catalog Layout ✅

`public/index.php` displays **6 book cards** arranged using Bootstrap's `row` / `col-md-4` grid. Each card contains an image, title, short description, price, and a "View Details" button.

```html
<div class="row">
    <!-- Book 1 -->
    <div class="col-md-4 mb-4 book-card-wrapper">
        <div class="card book-card">
            <img src="images/item1.jpg" class="card-img-top" alt="The Great Gatsby">
            <div class="card-body">
                <h5 class="card-title">The Great Gatsby</h5>
                <p class="card-text">A portrait of the Jazz Age and a cautionary tale of the American Dream.</p>
                <div class="book-price">$12.99</div>
                <a href="../pages/item_details.php?id=1" class="btn-view">View Details</a>
            </div>
        </div>
    </div>
    <!-- ... 5 more cards (item2.jpg – item6.jpg) ... -->
</div>
```

All 6 books (The Great Gatsby, To Kill a Mockingbird, 1984, Pride and Prejudice, The Hobbit, The Catcher in the Rye) are fully rendered.

> Source: `public/index.php`, lines 50–130.

---

## Task 6 — Item Details Page ✅

`pages/item_details.php` displays full book details (name, image, description, price, category, author) fetched dynamically via a PHP `$books` array and a `?id=` query parameter.

```php
<?php
$books = [
    1 => [
        'name'        => 'The Great Gatsby',
        'author'      => 'F. Scott Fitzgerald',
        'image'       => '../public/images/item1.jpg',
        'description' => 'The Great Gatsby is a 1925 novel ...',
        'price'       => '$12.99',
        'category'    => 'Classic Literature',
    ],
    // ... entries 2–6 ...
];
$id   = isset($_GET['id']) ? (int) $_GET['id'] : 1;
$book = $books[$id];
?>

<h2><?php echo $book['name']; ?></h2>
<p>Price: <?php echo $book['price']; ?></p>
<p><?php echo $book['description']; ?></p>
```

> Source: `pages/item_details.php`, lines 33–109.

---

## Task 7 — Basic JavaScript Interaction ✅

`public/js/script.js` defines `showMessage()` which displays a toast notification. It is triggered by:

1. **"Add to Cart"** button on the details page:

```html
<!-- pages/item_details.php, line 107 -->
<button class="btn-view" onclick="showMessage('<?php echo $book['name']; ?> added to cart!')">
    Add to Cart
</button>
```

2. **Contact form** submit button on the homepage:

```html
<!-- public/index.php, line 141 -->
<form onsubmit="event.preventDefault(); showMessage('Thank you! We will get back to you soon.');">
    ...
    <button type="submit" class="btn-submit">Send Message</button>
</form>
```

The JavaScript itself (`public/js/script.js`):

```js
function showMessage(message) {
    var toast = document.createElement('div');
    toast.className = 'custom-toast';
    toast.innerHTML = '<div class="toast-content">' + message + '</div>';
    document.body.appendChild(toast);

    setTimeout(function () { toast.classList.add('show'); }, 10);
    setTimeout(function () {
        toast.classList.remove('show');
        setTimeout(function () { toast.remove(); }, 300);
    }, 3000);
}
```

The script is loaded at the bottom of both pages:

```html
<script src="js/script.js"></script>
```

Additionally, a live **search / filter** feature is implemented that filters book cards by title as the user types — also defined in `script.js` (`filterBooks()` function, lines 35–52).

> Source: `public/js/script.js`, lines 7–27; `pages/item_details.php`, line 107; `public/index.php`, line 141.

---

## Summary

| Task | Requirement | Status |
|------|-------------|--------|
| 1 | Project folder structure | ✅ Done |
| 2 | `public/index.php` with HTML5 structure | ✅ Done |
| 3 | Header + navbar with logo & 4 nav links | ✅ Done |
| 4 | Bootstrap 5.3.0 CDN added | ✅ Done |
| 5 | Item catalog with ≥ 6 Bootstrap cards | ✅ Done (6 cards) |
| 6 | `pages/item_details.php` with name, image, description, price, category | ✅ Done |
| 7 | `public/js/script.js` with `showMessage()` attached to button | ✅ Done |

All **7 tasks** of Lab 2 are fully implemented in the **RysuReads** project.
