-- RysuReads — Database Setup
-- Run this once in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS rysureads
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE rysureads;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(150) NOT NULL,
    email    VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Items table
CREATE TABLE IF NOT EXISTS items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150)   NOT NULL,
    price       DECIMAL(10,2)  NOT NULL,
    category_id INT,
    description TEXT,
    image       VARCHAR(255)   DEFAULT NULL,
    created_at  DATETIME       DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Seed default categories
INSERT IGNORE INTO categories (id, name) VALUES
    (1, 'Classic Literature'),
    (2, 'Classic Fiction'),
    (3, 'Dystopian Fiction'),
    (4, 'Romance'),
    (5, 'Fantasy'),
    (6, 'Coming-of-Age');
