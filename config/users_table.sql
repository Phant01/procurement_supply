-- ============================================================
-- USERS TABLE  (run this AFTER supply_inventory_schema.sql)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    user_id       INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    personnel_id  INT UNSIGNED  DEFAULT NULL,
    username      VARCHAR(80)   NOT NULL,
    password      VARCHAR(255)  NOT NULL   COMMENT 'bcrypt hash',
    full_name     VARCHAR(200)  NOT NULL,
    role          ENUM('admin','supply_officer','viewer') NOT NULL DEFAULT 'viewer',
    is_active     TINYINT(1)    NOT NULL DEFAULT 1,
    last_login    DATETIME      DEFAULT NULL,
    created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (user_id),
    UNIQUE KEY uq_username (username),
    CONSTRAINT fk_user_personnel
        FOREIGN KEY (personnel_id) REFERENCES personnel (personnel_id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Default admin account  (password: Admin@1234)
-- Change this immediately after first login!
INSERT IGNORE INTO users (username, password, full_name, role)
VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'System Administrator',
    'admin'
);
