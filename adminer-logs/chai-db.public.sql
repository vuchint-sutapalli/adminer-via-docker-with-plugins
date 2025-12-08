DROP TABLE employee

CREATE TABLE employee(
  name VARCHAR(25) PRIMARY KEY
  age INT NOT NULL
  salary INT
)  

CREATE TABLE employee(
  name VARCHAR(25) PRIMARY KEY
  age INT NOTNULL
  salary INT
)  

CREATE TABLE employee(
name VARCHAR(25) PRIMARY KEY
age INT NOTNULL
salary INT
)  

CREATE TABLE employee(
name VARCHAR(25) PRIMARY KEY,
age INT NOT NULL,
salary INT
)  

-- 2025-12-07 18:53:07 | Status: SUCCESS
INSERT INTO employee(name,age,salary) VALUES('dfdf',33,40000),('ewe',44,66000);



-- 2025-12-08 04:39:56 | Status: SUCCESS
SHOW DATABASES;



-- 2025-12-08 04:40:19 | Status: SUCCESS
SHOW TABLES;



-- 2025-12-08 04:41:46 | Status: SUCCESS
SELECT table_name
FROM information_schema.tables
WHERE table_schema = 'public';



