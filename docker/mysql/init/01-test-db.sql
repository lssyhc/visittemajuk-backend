-- Init script mounted into the MySQL container's /docker-entrypoint-initdb.d.
-- Runs once on first volume creation (when /var/lib/mysql is empty).
-- Creates the test database and grants the application user access to it,
-- since the official mysql image only grants MYSQL_USER on MYSQL_DATABASE.

CREATE DATABASE IF NOT EXISTS visittemajuk_test
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON visittemajuk_test.* TO 'visittemajuk'@'%';

FLUSH PRIVILEGES;
