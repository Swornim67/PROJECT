CREATE DATABASE quickeat;
USE quickeat;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'customer'
);

-- Restaurants table
CREATE TABLE restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    category VARCHAR(100),
    delivery_time INT DEFAULT 30
);

-- Menu items table
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT,
    name VARCHAR(100),
    price DECIMAL(10,2),
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    restaurant_id INT,
    item_name VARCHAR(200),
    quantity INT,
    total DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
);

INSERT INTO restaurants (name, category, delivery_time) VALUES
('Momo House', 'Nepali', 25),
('Pizza Palace', 'Italian', 35),
('Burger Bro', 'Fast Food', 20);

INSERT INTO menu_items (restaurant_id, name, price) VALUES
(1, 'Steam Momo (8 pcs)', 180),
(1, 'Fried Momo (8 pcs)', 200),
(1, 'C-Momo', 220),
(2, 'Margherita Pizza', 450),
(2, 'Chicken Pizza', 550),
(3, 'Veg Burger', 200),
(3, 'Chicken Burger', 250);
