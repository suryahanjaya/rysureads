# RysuReads Project Report Outline

## 1. Introduction
- Purpose: responsive online catalog for books/items
- Target users: students, readers, and store visitors

## 2. Design Decisions
- Layout width: fluid Bootstrap container with max-width behavior from cards and sections
- Responsive strategy: Bootstrap grid + custom media queries
- Visual style: warm neutral background with blue accent for contrast and readability

## 3. Database Design
- `users`: authentication accounts, hashed passwords, reset tokens
- `categories`: item grouping and breadcrumb navigation
- `items`: product records with slug, price, rating, image, foreign key to categories
- `item_locations`: store availability and Google Maps links

## 4. Feature Implementation
- AJAX search: live fetch from `search_items.php`
- Sorting and pagination: server-side logic on `items.php`
- Authentication: register, login, logout, forgot/reset password
- SEO: page titles, meta descriptions, semantic HTML, sitemap, robots.txt

## 5. Challenges and Lessons Learned
- Relative path handling between `public/` and `pages/`
- Safe prepared statements and password hashing
- Keeping search responsive without page reload

## 6. Conclusion
- Summary of requirements covered and what is demonstrated in the lab
