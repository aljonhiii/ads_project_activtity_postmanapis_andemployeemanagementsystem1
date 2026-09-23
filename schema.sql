CREATE DATABASE IF NOT EXISTS employee_db;
USE employee_db;

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_initial CHAR(1),
    mobile_number VARCHAR(15) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    sex ENUM('Male', 'Female') NOT NULL,
    job_title VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample employee records
INSERT INTO employees (first_name, last_name, middle_initial, mobile_number, email, sex, job_title) VALUES
('John', 'Doe', 'A', '09123456789', 'john.doe@example.com', 'Male', 'Front End Developer'),
('Marie', 'Smith', 'B', '09234567890', 'marie.smith@example.com', 'Female', 'Business Analyst'),
('James', 'Brown', 'C', '09345678901', 'james.brown@example.com', 'Male', 'Quality Assurance Engineer'),
('Emily', 'Johnson', 'D', '09456789012', 'emily.johnson@example.com', 'Female', 'Business Analyst'),
('Michael', 'Williams', 'E', '09567890123', 'michael.williams@example.com', 'Male', 'Front End Developer'),
('Sophia', 'Davis', 'F', '09678901234', 'sophia.davis@example.com', 'Female', 'Quality Assurance Engineer'),
('William', 'Taylor', 'G', '09789012345', 'william.taylor@example.com', 'Male', 'Project Manager')
ON DUPLICATE KEY UPDATE email=email;
