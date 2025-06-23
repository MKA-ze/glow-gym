-- glow_gym.sql

CREATE DATABASE IF NOT EXISTS glow_gym;
USE glow_gym;

-- Users table (with email)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Classes table (with price and available_spots)
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    class_date DATE NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    available_spots INT NOT NULL DEFAULT 0
);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    class_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Sample users (now with email)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@gmail.com', MD5('admin123'), 'admin'),

-- Sample classes (using available_spots)
INSERT INTO classes (title, description, class_date, price, available_spots) VALUES
('Meditation 101', 'Learn how to meditate', '2025-07-10', 8.00, 20);