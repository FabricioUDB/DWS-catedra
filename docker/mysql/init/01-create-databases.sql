CREATE DATABASE IF NOT EXISTS `adacecam_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS `adacecam_test` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON `adacecam_db`.* TO 'adacecam_user'@'%';
GRANT ALL PRIVILEGES ON `adacecam_test`.* TO 'adacecam_user'@'%';

FLUSH PRIVILEGES;
