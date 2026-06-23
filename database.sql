CREATE DATABASE it_tickets;
USE it_tickets;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    pass VARCHAR(32) NOT NULL,
    role ENUM('EMP', 'IT') NOT NULL,
    is_busy TINYINT DEFAULT 0
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_email VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    screenshot VARCHAR(255) DEFAULT NULL,
    status ENUM('on_hold', 'assigned', 'closed') NOT NULL DEFAULT 'on_hold',
    work_status ENUM('not_started', 'in_progress', 'waiting', 'solved') DEFAULT 'not_started',
    it_notes TEXT DEFAULT NULL,
    assigned_to VARCHAR(100) DEFAULT NULL,
    created_at DATETIME NOT NULL,
    closed_at DATETIME DEFAULT NULL,
    closed_by VARCHAR(100) DEFAULT NULL
);

