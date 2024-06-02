-- Creating a user for the database
CREATE USER 'ecommerceusr'@'localhost' IDENTIFIED BY 'ecommercepsw';

-- Creating the ecommerce database
CREATE DATABASE ecommerce
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;


-- Creating a user for the database
CREATE USER 'testerusr'@'localhost' IDENTIFIED BY 'testerpsw';

-- Granting permissions to the testerusr, which DROP option
GRANT SELECT, INSERT, UPDATE, DELETE, DROP ON ecommerce.* TO 'testerusr'@'localhost';

-- Granting permissions to the ecommerceusr, CRUD grants
GRANT SELECT, INSERT, UPDATE, DELETE ON ecommerce.* TO 'ecommerceusr'@'localhost';

-- Selecting the ecommerce database
USE ecommerce;

-- Creating the users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'registered',
    email VARCHAR(100) NOT NULL,
    dob DATE NOT NULL
) ENGINE=InnoDb;

-- Creating the products table
CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR (10) UNIQUE,
    description VARCHAR(100) NOT NULL,
    price FLOAT
) ENGINE=InnoDb;

-- Creating the orders table
CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    creationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    delMethod VARCHAR(50) NOT NULL,
    customer INTEGER references users(id) ON DELETE restrict ON UPDATE cascade
) ENGINE=InnoDb;

-- Creating the orders items table
CREATE TABLE orderitems (
    orderId INTEGER NOT NULL references orders(id) ON DELETE cascade ON UPDATE cascade,
    productId INTEGER NOT NULL references products(id) ON DELETE restrict ON UPDATE cascade,
    quantity INTEGER NOT NULL,
    unitPrice FLOAT NOT NULL,
    PRIMARY KEY (orderId, productId)
) ENGINE=InnoDb;

-- Inserting sample users
INSERT INTO users (username, password, role, email, dob) VALUES 
    ('user01', 'pass01', 'admin', 'user01@info.com', '1980-01-01'),
    ('user02', 'pass02', 'registered', 'user02@info.com', '1985-02-02'),
    ('user03', 'pass03', 'registered', 'user03@info.com', '1990-03-03'),
    ('user04', 'pass04', 'registered', 'user04@info.com', '1995-04-04'),
    ('user05', 'pass05', 'registered', 'user05@info.com', '1996-05-05'),
    ('user06', 'pass06', 'registered', 'user06@info.com', '1997-06-06'),
    ('user07', 'pass07', 'registered', 'user07@info.com', '1998-07-07'),
    ('user08', 'pass08', 'registered', 'user08@info.com', '1999-08-08'),
    ('user09', 'pass09', 'registered', 'user09@info.com', '2000-09-09'),
    ('user10', 'pass10', 'registered', 'user10@info.com', '2001-10-10'),
    ('user11', 'pass11', 'registered', 'user11@info.com', '2002-11-11'),
    ('user12', 'pass12', 'registered', 'user12@info.com', '2003-12-12'),
    ('user13', 'pass13', 'registered', 'user13@info.com', '2004-01-13');


-- Inserting sample products
INSERT INTO products (code, description, price) VALUES 
    ('BIO101', 'DNA Sequencing Kit', 299.99),
    ('BIO102', 'Protein Synthesis Set', 199.99),
    ('BIO103', 'CRISPR Gene Editing Tool', 499.99);


-- Adding sample orders
INSERT INTO orders (delMethod, customer) VALUES
    ('Ship to address', 1),
    ('Click and collect', 2);

INSERT INTO orderitems(orderId, productId, quantity, unitPrice) VALUES
 (1, 1, 1, 11.1),
 (1, 2, 2, 44.4),
 (1, 3, 3, 99.9);