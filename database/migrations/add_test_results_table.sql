-- Migration: Create test_results table for storing results with QR codes
USE evaluation_db;

CREATE TABLE IF NOT EXISTS test_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(64) NOT NULL UNIQUE,
    test_token VARCHAR(64),
    score INT NOT NULL,
    total_questions INT NOT NULL,
    result_details JSON NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NULL,
    KEY idx_token (token),
    KEY idx_test_token (test_token),
    KEY idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
