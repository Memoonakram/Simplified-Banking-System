-- config/create_database.sql
CREATE DATABASE IF NOT EXISTS login_system;

USE login_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Insert sample admin and user data with hashed passwords
INSERT INTO users (email, password, role)
VALUES
('ebraheemgillani1@gmail.com', PASSWORD('admin_password'), 'admin'),
('ebraheemgillani@gmail.com', PASSWORD('user_password'), 'user');
