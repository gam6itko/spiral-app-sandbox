-- Schema dump (Cycle ORM charset/collation check)
-- Database: app_sandbox
-- Date: 2026-03-13 08:16:57

CREATE TABLE `user_charset_check` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- information_schema.COLUMNS for user_charset_check.email (charset/collation):
--   email: COLUMN_TYPE=varchar(255), CHARACTER_SET_NAME=utf8mb4, COLLATION_NAME=utf8mb4_0900_ai_ci
