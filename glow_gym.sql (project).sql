
CREATE DATABASE IF NOT EXISTS glow_gym;
USE glow_gym;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    class_date DATE NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    available_spots INT NOT NULL DEFAULT 0
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    class_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@gmail.com', MD5('admin123'), 'admin'),

INSERT INTO classes (title, description, class_date, price, available_spots) VALUES
('Meditation', 'Learn how to meditate', '2025-07-10', 20.00, 20);
