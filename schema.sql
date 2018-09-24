CREATE DATABASE doingsdone;
USE doingsdone;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
registration DATETIME,
email CHAR(128) NOT NULL,
name CHAR(128) NOT NULL,
password CHAR(128) NOT NULL,
contacts CHAR(128)
);

CREATE TABLE projects (
title CHAR(128) PRIMARY KEY NOT NULL,
author INT,
FOREIGN KEY (author) REFERENCES users(id)
);

CREATE TABLE tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
realization DATETIME,
creation DATETIME,
title CHAR(128) NOT NULL,
term DATETIME,
task_file BINARY,
task_status ENUM('0','1') DEFAULT '0',
author INT,
project INT,
FOREIGN KEY (author) REFERENCES users(id)
);

CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX task_date ON tasks(title, term);