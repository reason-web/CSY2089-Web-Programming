CREATE DATABASE IF NOT EXISTS assignment1;
USE assignment1;

CREATE TABLE IF NOT EXISTS user (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    isAdmin TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS category (
    categoryId INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS auction (
    auctionId INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    categoryId INT,
    endDate DATETIME NOT NULL,
    userId INT NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES category(categoryId),
    FOREIGN KEY (userId) REFERENCES user(userId)
);

CREATE TABLE IF NOT EXISTS review (
    reviewId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    reviewerId INT NOT NULL,
    reviewText TEXT NOT NULL,
    reviewDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES user(userId),
    FOREIGN KEY (reviewerId) REFERENCES user(userId)
);

CREATE TABLE IF NOT EXISTS bid (
    bidId INT AUTO_INCREMENT PRIMARY KEY,
    auctionId INT NOT NULL,
    userId INT NOT NULL,
    bid DECIMAL(10,2) NOT NULL,
    bidDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (auctionId) REFERENCES auction(auctionId),
    FOREIGN KEY (userId) REFERENCES user(userId)
);