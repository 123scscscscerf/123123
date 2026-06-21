CREATE DATABASE IF NOT EXISTS comfy_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE comfy_shop;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(40) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    description TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(160) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'assets/images/placeholder.svg',
    is_popular TINYINT(1) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_categories FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(32) NOT NULL UNIQUE,
    user_id INT NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(180) NOT NULL,
    customer_phone VARCHAR(40) NOT NULL,
    delivery_address VARCHAR(255) NOT NULL,
    payment_method VARCHAR(80) NOT NULL,
    status ENUM('open','closed','cancelled') NOT NULL DEFAULT 'open',
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    order_date DATE NOT NULL,
    delivery_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_items_orders FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_items_products FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO users (name, email, password, phone, address) VALUES
('Demo Demo', 'demo@mail.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', '+1-111-111-11-11', 'London, UK 847 Jewess Bridge Apt. 174');

INSERT INTO categories (title, description) VALUES
('Народные', 'Сувениры с национальным колоритом'),
('Статуэтки', 'Миниатюрные декоративные фигурки'),
('Авторские', 'Уникальные изделия ручной работы'),
('Декоративные', 'Предметы для интерьера'),
('Оригинальные', 'Необычные памятные подарки'),
('Брелки', 'Компактные сувениры на каждый день');

INSERT INTO products (category_id, title, description, price, image, is_popular, stock) VALUES
(1, 'Сувенир 1', 'Популярный народный сувенир', 500, 'assets/images/placeholder.svg', 1, 15),
(2, 'Сувенир 2', 'Аккуратная статуэтка', 300, 'assets/images/placeholder.svg', 0, 20),
(3, 'Сувенир 3', 'Авторский сувенир', 1000, 'assets/images/placeholder.svg', 1, 7),
(4, 'Сувенир 4', 'Декоративный сувенир', 700, 'assets/images/placeholder.svg', 0, 11),
(5, 'Сувенир 5', 'Оригинальный подарок', 800, 'assets/images/placeholder.svg', 1, 9),
(6, 'Сувенир 6', 'Брелок COMFY', 400, 'assets/images/placeholder.svg', 0, 30);

INSERT INTO orders (order_number, user_id, customer_name, customer_email, customer_phone, delivery_address, payment_method, status, total, order_date, delivery_date) VALUES
('334910264', 1, 'Demo Demo', 'demo@mail.ru', '87777777777', 'Павлодарская область', 'Visa **56', 'open', 500, '2024-03-16', '2024-03-30'),
('2', 1, 'Demo Demo', 'demo@mail.ru', '+1-111-111-11-11', 'London, UK', 'Visa **56', 'closed', 500, '2024-03-07', '2024-03-19'),
('3', 1, 'Demo Demo', 'demo@mail.ru', '+1-111-111-11-11', 'London, UK', 'Visa **56', 'closed', 300, '2024-03-07', '2024-03-19');

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 500), (2, 1, 1, 500), (3, 2, 1, 300);
