#SQL file created in collaboration between Dane and Tristan
DROP
    DATABASE IF EXISTS task_app;
CREATE DATABASE task_app; USE
    task_app;
CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    NAME VARCHAR(255),
    #different from user name
    email VARCHAR(255),
    isAdmin BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
#Table responsible for checking if user exists, and if their password is correct. 
#Password not stored for security
CREATE TABLE userlogin(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    passHASH BIGINT NOT NULL,
    userID INT REFERENCES users(id)
); CREATE TABLE tasks(
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT REFERENCES users(id),
    title VARCHAR(255),
    description TEXT,
    priority VARCHAR(20),
    STATUS VARCHAR
        (20),
        task_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE notifications(
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT REFERENCES users(id),
    message TEXT,
    is_read BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE forms
(
    ClientName VARCHAR(60) NOT NULL,
    ClientEmail VARCHAR(60) NOT NULL,
    Reason ENUM('account', 'ad', 'feedback', 'other') NOT NULL,
    CustomReason VARCHAR(60) DEFAULT NULL,
    ClientConcern VARCHAR(500) NOT NULL,
    FormID INT AUTO_INCREMENT PRIMARY KEY,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
