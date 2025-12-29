CREATE DATABASE IF NOT EXISTS laravel_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'laravel_user'@'%'
  IDENTIFIED BY 'laravel_password';

GRANT ALL PRIVILEGES ON laravel_db.* TO 'laravel_user'@'%';

FLUSH PRIVILEGES;
