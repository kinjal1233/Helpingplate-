CREATE DATABASE IF NOT EXISTS helpingplate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE helpingplate;

CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 phone VARCHAR(25) NOT NULL,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('donor','requester','volunteer','admin') NOT NULL,
 status ENUM('active','suspended') NOT NULL DEFAULT 'active',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_users_role_status(role,status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_profiles (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL UNIQUE,
 address_line1 VARCHAR(255) NULL, city VARCHAR(100) NULL, state VARCHAR(100) NULL, pincode VARCHAR(20) NULL,
 organization_name VARCHAR(150) NULL, profile_image VARCHAR(255) NULL, bio TEXT NULL,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_profiles_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS donations (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 donor_id INT UNSIGNED NOT NULL,
 food_title VARCHAR(150) NOT NULL, category VARCHAR(60) NOT NULL,
 quantity INT UNSIGNED NOT NULL, quantity_unit VARCHAR(30) NOT NULL DEFAULT 'servings', available_quantity INT UNSIGNED NOT NULL,
 description TEXT NOT NULL, prepared_at DATETIME NOT NULL, expiry_at DATETIME NOT NULL,
 pickup_address TEXT NOT NULL, city VARCHAR(100) NOT NULL, contact_name VARCHAR(100) NOT NULL, contact_phone VARCHAR(25) NOT NULL,
 image_path VARCHAR(255) NULL,
 status ENUM('pending','approved','rejected','partially_claimed','fully_claimed','expired','cancelled') NOT NULL DEFAULT 'pending',
 admin_notes TEXT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_donations_donor FOREIGN KEY(donor_id) REFERENCES users(id) ON DELETE CASCADE,
 INDEX idx_donations_available(status,expiry_at,city), INDEX idx_donations_donor(donor_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS food_requests (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 donation_id INT UNSIGNED NOT NULL, requester_id INT UNSIGNED NOT NULL,
 requested_quantity INT UNSIGNED NOT NULL, purpose TEXT NOT NULL,
 status ENUM('pending','approved','rejected','assigned','completed','cancelled') NOT NULL DEFAULT 'pending',
 admin_notes TEXT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_requests_donation FOREIGN KEY(donation_id) REFERENCES donations(id) ON DELETE CASCADE,
 CONSTRAINT fk_requests_requester FOREIGN KEY(requester_id) REFERENCES users(id) ON DELETE CASCADE,
 INDEX idx_requests_status(status), INDEX idx_requests_requester(requester_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS volunteer_applications (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NULL,
 name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, phone VARCHAR(25) NOT NULL,
 preferred_area VARCHAR(150) NOT NULL, availability VARCHAR(150) NOT NULL,
 vehicle_type VARCHAR(40) NOT NULL DEFAULT 'None', experience TEXT NULL,
 status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending', admin_remarks TEXT NULL,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, reviewed_at DATETIME NULL, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_volunteer_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
 UNIQUE KEY uq_volunteer_user(user_id), INDEX idx_volunteer_status(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS deliveries (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, request_id INT UNSIGNED NOT NULL UNIQUE,
 volunteer_id INT UNSIGNED NULL,
 pickup_time DATETIME NULL, delivery_time DATETIME NULL,
 status ENUM('unassigned','assigned','accepted','picked_up','in_transit','delivered','failed') NOT NULL DEFAULT 'unassigned',
 delivery_notes TEXT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_delivery_request FOREIGN KEY(request_id) REFERENCES food_requests(id) ON DELETE CASCADE,
 CONSTRAINT fk_delivery_volunteer FOREIGN KEY(volunteer_id) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_delivery_status(status,volunteer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS feedback (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, type ENUM('feedback','contact') NOT NULL DEFAULT 'feedback',
 name VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL,phone VARCHAR(25) NULL,
 category VARCHAR(80) NOT NULL DEFAULT 'General', rating TINYINT UNSIGNED NULL,
 message TEXT NOT NULL, allow_contact TINYINT(1) NOT NULL DEFAULT 0,
 status ENUM('unread','read','archived') NOT NULL DEFAULT 'unread', created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_feedback_type_status(type,status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_messages (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(150) NOT NULL,phone VARCHAR(25) NULL,
 subject VARCHAR(80) NOT NULL,message TEXT NOT NULL,status ENUM('unread','read','archived') NOT NULL DEFAULT 'unread',
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notifications (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id INT UNSIGNED NOT NULL,title VARCHAR(150) NOT NULL,message TEXT NOT NULL,link VARCHAR(255) NULL,
 is_read TINYINT(1) NOT NULL DEFAULT 0,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_notifications_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 INDEX idx_notifications_user_read(user_id,is_read,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS activity_logs (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,user_id INT UNSIGNED NULL,action VARCHAR(100) NOT NULL,description TEXT NOT NULL,
 ip_address VARCHAR(45) NULL,created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_logs_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_logs_created(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS app_settings (
 setting_key VARCHAR(80) NOT NULL PRIMARY KEY,
 setting_value TEXT NOT NULL,
 updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_resets (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL,
 token_hash CHAR(64) NOT NULL UNIQUE,
 request_ip VARCHAR(45) NULL,
 requested_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 expires_at DATETIME NOT NULL,
 used_at DATETIME NULL,
 CONSTRAINT fk_password_resets_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 INDEX idx_password_resets_user_time(user_id, requested_at),
 INDEX idx_password_resets_expiry(expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
