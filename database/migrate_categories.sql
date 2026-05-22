-- Run once in phpMyAdmin on database "emotimate" if you already imported emotimate.sql before categories existed.

CREATE TABLE IF NOT EXISTS test_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE tests
    ADD COLUMN IF NOT EXISTS category_id INT NULL AFTER description;

-- MySQL 8.0.12+ supports IF NOT EXISTS on ADD COLUMN; if error, run manually:
-- ALTER TABLE tests ADD COLUMN category_id INT NULL AFTER description;

ALTER TABLE tests
    ADD CONSTRAINT fk_tests_category
    FOREIGN KEY (category_id) REFERENCES test_categories (id) ON DELETE SET NULL;

INSERT INTO test_categories (name, description, sort_order) VALUES
('Schema Tests', 'Assessments for early maladaptive schemas and related thinking patterns.', 1),
('Depression and Anxiety Scales', 'Scales measuring depression and anxiety symptoms.', 2),
('Emotion and Mood Scales', 'Scales for emotion regulation and mood evaluation.', 3),
('Attention and Impulsivity Tests', 'Scales for attention, hyperactivity, and impulsivity.', 4),
('Post-Traumatic Stress Disorder', 'Scales related to PTSD and post-trauma symptoms.', 5)
ON DUPLICATE KEY UPDATE name = VALUES(name);

ALTER TABLE tests
    ADD COLUMN IF NOT EXISTS instructions TEXT NULL AFTER description;
