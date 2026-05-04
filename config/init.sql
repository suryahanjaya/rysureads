-- RysuReads project database
-- Import this file into MySQL to create tables and sample content.

CREATE DATABASE IF NOT EXISTS project_database
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE project_database;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(100) DEFAULT NULL,
    reset_expires_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL,
    rating DECIMAL(3,1) NOT NULL DEFAULT 4.5,
    category_id INT NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS item_locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    location_name VARCHAR(150) NOT NULL,
    address VARCHAR(255) NOT NULL,
    map_url VARCHAR(255) NOT NULL,
    availability_note VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT IGNORE INTO categories (id, name, slug) VALUES
    (1, 'Classic Literature', 'classic-literature'),
    (2, 'Classic Fiction', 'classic-fiction'),
    (3, 'Dystopian Fiction', 'dystopian-fiction'),
    (4, 'Romance', 'romance'),
    (5, 'Fantasy', 'fantasy'),
    (6, 'Coming-of-Age', 'coming-of-age');

INSERT IGNORE INTO items (id, name, slug, price, rating, category_id, description, image) VALUES
    (1, 'The Great Gatsby', 'the-great-gatsby', 12.99, 4.8, 1, 'A portrait of the Jazz Age and a cautionary tale of the American Dream.', 'images/item1.jpg'),
    (2, 'To Kill a Mockingbird', 'to-kill-a-mockingbird', 14.50, 4.9, 2, 'A gripping tale of racial injustice and childhood innocence in the American South.', 'images/item2.jpg'),
    (3, '1984', '1984', 11.99, 4.9, 3, 'A dystopian masterpiece about surveillance, propaganda, and the erosion of truth.', 'images/item3.jpg'),
    (4, 'Pride and Prejudice', 'pride-and-prejudice', 10.99, 4.7, 4, 'A witty exploration of manners, morality, and marriage in Regency-era England.', 'images/item4.jpg'),
    (5, 'The Hobbit', 'the-hobbit', 15.99, 4.9, 5, 'An epic quest through Middle-earth filled with dragons, dwarves, and adventure.', 'images/item5.jpg'),
    (6, 'The Catcher in the Rye', 'the-catcher-in-the-rye', 13.49, 4.4, 6, 'A coming-of-age story about adolescence, identity, and belonging.', 'images/item6.jpg');

INSERT IGNORE INTO item_locations (id, item_id, location_name, address, map_url, availability_note) VALUES
    (1, 1, 'District 1 Store', '41 Nguyen Hue, District 1, Ho Chi Minh City', 'https://maps.google.com/?q=41+Nguyen+Hue,+District+1,+Ho+Chi+Minh+City', 'In stock'),
    (2, 1, 'Thu Duc Branch', '11 Vo Van Ngan, Thu Duc City', 'https://maps.google.com/?q=11+Vo+Van+Ngan,+Thu+Duc+City', 'Limited stock'),
    (3, 2, 'District 3 Store', '220 Vo Thi Sau, District 3, Ho Chi Minh City', 'https://maps.google.com/?q=220+Vo+Thi+Sau,+District+3,+Ho+Chi+Minh+City', 'In stock'),
    (4, 3, 'District 1 Store', '41 Nguyen Hue, District 1, Ho Chi Minh City', 'https://maps.google.com/?q=41+Nguyen+Hue,+District+1,+Ho+Chi+Minh+City', 'In stock'),
    (5, 4, 'District 5 Store', '905 Tran Hung Dao, District 5, Ho Chi Minh City', 'https://maps.google.com/?q=905+Tran+Hung+Dao,+District+5,+Ho+Chi+Minh+City', 'In stock'),
    (6, 5, 'Thu Duc Branch', '11 Vo Van Ngan, Thu Duc City', 'https://maps.google.com/?q=11+Vo+Van+Ngan,+Thu+Duc+City', 'In stock'),
    (7, 6, 'District 7 Store', '2 Tan Trao, District 7, Ho Chi Minh City', 'https://maps.google.com/?q=2+Tan+Trao,+District+7,+Ho+Chi+Minh+City', 'Pre-order');

-- Sample user password hash: password123
INSERT IGNORE INTO users (id, name, email, password) VALUES
    (1, 'Admin User', 'admin@rysureads.local', '$2y$12$qIbY6/nMNZLZoqLiJV4Vm.iGz499Dt0WZPWyxEfDwno6VeMokhX12');
