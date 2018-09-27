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
id INT AUTO_INCREMENT PRIMARY KEY,
title CHAR(128) NOT NULL,
author_id INT,
FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
realization DATETIME,
creation DATETIME DEFAULT NOW(),
title CHAR(128) NOT NULL,
term DATETIME,
task_file CHAR(255),
task_status ENUM('0','1') DEFAULT '0',
author_id INT,
project_id INT,
FOREIGN KEY (project_id) REFERENCES projects(id),
FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX task_date ON tasks(title, term);