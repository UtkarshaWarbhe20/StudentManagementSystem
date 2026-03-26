-- ============================================================
--  schema.sql — Run this on your MySQL server to set up SMS DB
--  Command: mysql -u root -p < schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS sms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sms_db;

-- Admin table
CREATE TABLE IF NOT EXISTS admins (
    id         VARCHAR(20)  PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id         VARCHAR(20)  PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    department VARCHAR(60),
    year       VARCHAR(20),
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Notices table
CREATE TABLE IF NOT EXISTS notices (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(200) NOT NULL,
    body       TEXT,
    tag        ENUM('general','exam','holiday','urgent') DEFAULT 'general',
    posted_by  VARCHAR(50),
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Schedules table
CREATE TABLE IF NOT EXISTS schedules (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    time_slot  VARCHAR(30)  NOT NULL,
    subject    VARCHAR(100) NOT NULL,
    teacher    VARCHAR(100) NOT NULL,
    room       VARCHAR(20),
    is_absent  TINYINT(1)   DEFAULT 0,
    sched_date DATE         NOT NULL DEFAULT (CURDATE()),
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Seed admin
INSERT IGNORE INTO admins (id, name, password)
VALUES ('ADMIN001', 'Dr. Rajesh Kumar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- ^ that hash = 'admin123'

-- Seed students (password = 'pass123')
INSERT IGNORE INTO students (id, name, email, password, department, year) VALUES
('STU001','Priya Sharma', 'priya@sms.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Computer Science','3rd Year'),
('STU002','Arjun Patel',  'arjun@sms.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Electronics','2nd Year'),
('STU003','Sneha Reddy',  'sneha@sms.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Mechanical','1st Year');

-- Seed notices
INSERT IGNORE INTO notices (title, body, tag, posted_by) VALUES
('Mid-Semester Examination Schedule','Exams from April 10-18. Carry ID cards.','exam','Admin'),
('Holi Holiday','College closed March 25.','holiday','Admin'),
('Fee Payment Deadline','Pay by March 28 to avoid fine.','urgent','Admin');

-- Seed today's schedule
INSERT IGNORE INTO schedules (time_slot, subject, teacher, room, sched_date) VALUES
('8:00-9:00',  'Data Structures',  'Prof. Meena Iyer',  'A-201', CURDATE()),
('9:00-10:00', 'Mathematics III',  'Prof. Suresh Naik', 'A-101', CURDATE()),
('10:15-11:15','Computer Networks','Prof. Anil Kumar',  'Lab-3', CURDATE());
