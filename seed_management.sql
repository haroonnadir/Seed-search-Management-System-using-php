CREATE DATABASE IF NOT EXISTS seed_management;
USE seed_management;

CREATE TABLE IF NOT EXISTS seeds (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seedId VARCHAR(30) NOT NULL UNIQUE,
    title VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    color VARCHAR(30) NOT NULL,
    costPerKg DECIMAL(10, 2) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO seeds (seedId, title, category, color, costPerKg) VALUES
('S001', 'Tomato', 'Vegetables', 'Red', 12.50),
('S002', 'Cucumber', 'Vegetables', 'Green', 10.00),
('S003', 'Sunflower', 'Flowers', 'Yellow', 15.75),
('S004', 'Basil', 'Herbs', 'Green', 22.00),
('S005', '  ', 'Fruits', 'Green', 18.50),
('S006', 'Wheat', 'Grains', 'Brown', 8.00);