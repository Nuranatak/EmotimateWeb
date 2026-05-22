CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE test_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO test_categories (name, description, sort_order) VALUES
('Schema Tests', 'Assessments for early maladaptive schemas and related thinking patterns.', 1),
('Depression and Anxiety Scales', 'Scales measuring depression and anxiety symptoms.', 2),
('Emotion and Mood Scales', 'Scales for emotion regulation and mood evaluation.', 3),
('Attention and Impulsivity Tests', 'Scales for attention, hyperactivity, and impulsivity.', 4),
('Post-Traumatic Stress Disorder', 'Scales related to PTSD and post-trauma symptoms.', 5);

CREATE TABLE tests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES test_categories (id) ON DELETE SET NULL
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    test_id INT NOT NULL,
    question_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (test_id) REFERENCES tests (id) ON DELETE CASCADE
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    test_id INT NOT NULL,
    score INT NOT NULL,
    interpretation VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (test_id) REFERENCES tests (id) ON DELETE CASCADE
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    value INT NOT NULL CHECK (value BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE
);