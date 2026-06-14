-- Traffic Violation Tracker schema (MySQL 8+)
-- Database name: trafficsystem (configure in config/config.php or env)

CREATE TABLE IF NOT EXISTS `user` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    licence_no VARCHAR(50) DEFAULT NULL,
    nic BIGINT NOT NULL,
    tel_no BIGINT NOT NULL,
    profile_pic VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS vehicle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id VARCHAR(255) NOT NULL,
    vehicle_no VARCHAR(255) NOT NULL,
    owner_id INT NOT NULL,
    model VARCHAR(255) NOT NULL,
    chassi_no VARCHAR(255) NOT NULL,
    eng_no VARCHAR(255) NOT NULL,
    CONSTRAINT fk_vehicle_owner FOREIGN KEY (owner_id) REFERENCES `user` (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS violation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id VARCHAR(255) NOT NULL,
    driver_id INT NOT NULL,
    violation_type VARCHAR(255) NOT NULL,
    fine_amount INT NOT NULL,
    status VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    vehicle_number VARCHAR(255) NOT NULL,
    incident_date DATETIME NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS citizen_report (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incident_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    description LONGTEXT NOT NULL,
    evidence_path VARCHAR(512) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Pending Review',
    created_at DATETIME NOT NULL,
    user_id INT DEFAULT NULL,
    CONSTRAINT fk_report_user FOREIGN KEY (user_id) REFERENCES `user` (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notification` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME NOT NULL,
    CONSTRAINT fk_notification_user FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

