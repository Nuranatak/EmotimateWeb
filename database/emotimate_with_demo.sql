-- EMOTIMATE — full schema + demo data export
-- Import in phpMyAdmin: database `emotimate`
-- Charset: utf8mb4

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS answers;
DROP TABLE IF EXISTS results;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS tests;
DROP TABLE IF EXISTS test_categories;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

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

-- Demo users (admin123 / user123)
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'EMOTIMATE Administrator', 'admin@emotimate.com', '$2y$10$iA0Vu2c0YRT//asChW/E2.g4.aQFCksMhgCFjX/bCSovxsBxzovsK', 'admin'),
(2, 'Sarah Mitchell', 'sarah.demo@emotimate.com', '$2y$10$U.pmEB9JWfw.affZ2nPviuaaA7DIW6j/73fbEslYh5GGIA4KJaNBe', 'user');

INSERT INTO tests (id, category_id, title, description, instructions) SELECT 1, id, 'Abandonment / Instability Schema Scale', 'Assesses fears that close relationships will end and emotional support will not continue.', NULL FROM test_categories WHERE name = 'Schema Tests' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (1, 1, 'I worry that people who are important to me will leave me.');
INSERT INTO questions (id, test_id, question_text) VALUES (2, 1, 'I feel that I will eventually lose the interest of people I care about.');
INSERT INTO questions (id, test_id, question_text) VALUES (3, 1, 'I feel insecure about whether people I love will stay with me.');
INSERT INTO questions (id, test_id, question_text) VALUES (4, 1, 'When someone I care about is away, I fear they will not return.');
INSERT INTO questions (id, test_id, question_text) VALUES (5, 1, 'I need other people more than they need me.');
INSERT INTO tests (id, category_id, title, description, instructions) SELECT 2, id, 'Mistrust / Abuse Schema Scale', 'Assesses expectations that others may hurt, manipulate, or take advantage.', NULL FROM test_categories WHERE name = 'Schema Tests' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (6, 2, 'I expect people to take advantage of me if I am not careful.');
INSERT INTO questions (id, test_id, question_text) VALUES (7, 2, 'I am suspicious of other people''s motives.');
INSERT INTO questions (id, test_id, question_text) VALUES (8, 2, 'I find it hard to trust people completely.');
INSERT INTO questions (id, test_id, question_text) VALUES (9, 2, 'I believe it is important to keep my guard up with most people.');
INSERT INTO questions (id, test_id, question_text) VALUES (10, 2, 'It is only a matter of time before someone betrays me.');
INSERT INTO tests (id, category_id, title, description, instructions) SELECT 3, id, 'Depression Test (PHQ-9)', 'Nine-item depression screening scale (Likert adaptation).', NULL FROM test_categories WHERE name = 'Depression and Anxiety Scales' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (11, 3, 'Little interest or pleasure in doing things.');
INSERT INTO questions (id, test_id, question_text) VALUES (12, 3, 'Feeling down, depressed, or hopeless.');
INSERT INTO questions (id, test_id, question_text) VALUES (13, 3, 'Trouble falling or staying asleep, or sleeping too much.');
INSERT INTO questions (id, test_id, question_text) VALUES (14, 3, 'Feeling tired or having little energy.');
INSERT INTO questions (id, test_id, question_text) VALUES (15, 3, 'Poor appetite or overeating.');
INSERT INTO questions (id, test_id, question_text) VALUES (16, 3, 'Feeling bad about yourself — or that you are a failure.');
INSERT INTO questions (id, test_id, question_text) VALUES (17, 3, 'Trouble concentrating on things.');
INSERT INTO questions (id, test_id, question_text) VALUES (18, 3, 'Moving or speaking slowly, or being fidgety/restless.');
INSERT INTO questions (id, test_id, question_text) VALUES (19, 3, 'Thoughts that you would be better off dead or hurting yourself.');
INSERT INTO tests (id, category_id, title, description, instructions) SELECT 4, id, 'Anxiety Test (GAD-7)', 'Seven-item generalized anxiety screening scale.', NULL FROM test_categories WHERE name = 'Depression and Anxiety Scales' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (20, 4, 'Feeling nervous, anxious, or on edge.');
INSERT INTO questions (id, test_id, question_text) VALUES (21, 4, 'Not being able to stop or control worrying.');
INSERT INTO questions (id, test_id, question_text) VALUES (22, 4, 'Worrying too much about different things.');
INSERT INTO questions (id, test_id, question_text) VALUES (23, 4, 'Trouble relaxing.');
INSERT INTO questions (id, test_id, question_text) VALUES (24, 4, 'Being so restless that it is hard to sit still.');
INSERT INTO questions (id, test_id, question_text) VALUES (25, 4, 'Becoming easily annoyed or irritable.');
INSERT INTO questions (id, test_id, question_text) VALUES (26, 4, 'Feeling afraid as if something awful might happen.');
INSERT INTO tests (id, category_id, title, description, instructions) SELECT 5, id, 'Emotional Awareness & Regulation Scale', 'Measures emotional awareness and regulation.', NULL FROM test_categories WHERE name = 'Emotion and Mood Scales' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (27, 5, 'I can usually identify what emotion I am feeling.');
INSERT INTO questions (id, test_id, question_text) VALUES (28, 5, 'I understand why I react emotionally in many situations.');
INSERT INTO questions (id, test_id, question_text) VALUES (29, 5, 'I can calm myself down when I feel upset.');
INSERT INTO questions (id, test_id, question_text) VALUES (30, 5, 'I express my feelings in healthy ways.');
INSERT INTO questions (id, test_id, question_text) VALUES (31, 5, 'I recover reasonably well after emotional stress.');
INSERT INTO questions (id, test_id, question_text) VALUES (32, 5, 'Strong emotions sometimes overwhelm me.');
INSERT INTO questions (id, test_id, question_text) VALUES (33, 5, 'I avoid talking about feelings even when it would help.');
INSERT INTO questions (id, test_id, question_text) VALUES (34, 5, 'I notice physical signs of stress early.');
INSERT INTO tests (id, category_id, title, description, instructions) SELECT 6, id, 'Personality Style Inventory', 'Brief personality style self-report inventory.', NULL FROM test_categories WHERE name = 'Emotion and Mood Scales' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (35, 6, 'I enjoy meeting new people and social gatherings.');
INSERT INTO questions (id, test_id, question_text) VALUES (36, 6, 'I prefer planning ahead rather than acting spontaneously.');
INSERT INTO questions (id, test_id, question_text) VALUES (37, 6, 'I stay calm under pressure most of the time.');
INSERT INTO questions (id, test_id, question_text) VALUES (38, 6, 'I appreciate art, ideas, and creative experiences.');
INSERT INTO questions (id, test_id, question_text) VALUES (39, 6, 'I go out of my way to help others.');
INSERT INTO questions (id, test_id, question_text) VALUES (40, 6, 'I pay close attention to details and organization.');
INSERT INTO questions (id, test_id, question_text) VALUES (41, 6, 'I am comfortable taking the lead in groups.');
INSERT INTO questions (id, test_id, question_text) VALUES (42, 6, 'I often reflect deeply before making decisions.');
INSERT INTO tests (id, category_id, title, description, instructions) SELECT 7, id, 'Attention & Focus Scale', 'Assesses concentration and focus.', NULL FROM test_categories WHERE name = 'Attention and Impulsivity Tests' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (43, 7, 'I can stay focused on tasks for a reasonable period.');
INSERT INTO questions (id, test_id, question_text) VALUES (44, 7, 'I complete projects I start.');
INSERT INTO questions (id, test_id, question_text) VALUES (45, 7, 'I am easily distracted by notifications or noise.');
INSERT INTO questions (id, test_id, question_text) VALUES (46, 7, 'I forget appointments or deadlines occasionally.');
INSERT INTO questions (id, test_id, question_text) VALUES (47, 7, 'I feel mentally organized when working.');
INSERT INTO questions (id, test_id, question_text) VALUES (48, 7, 'Long tasks feel exhausting to finish.');
INSERT INTO tests (id, category_id, title, description, instructions) SELECT 8, id, 'Trauma Re-Experiencing & Intrusive Thoughts Scale', 'Screens intrusive memories and trauma-related stress.', NULL FROM test_categories WHERE name = 'Post-Traumatic Stress Disorder' LIMIT 1;
INSERT INTO questions (id, test_id, question_text) VALUES (49, 8, 'Upsetting memories of stressful events come back unexpectedly.');
INSERT INTO questions (id, test_id, question_text) VALUES (50, 8, 'I have disturbing dreams related to difficult experiences.');
INSERT INTO questions (id, test_id, question_text) VALUES (51, 8, 'I feel upset when reminded of painful events.');
INSERT INTO questions (id, test_id, question_text) VALUES (52, 8, 'I avoid places or people that trigger difficult memories.');
INSERT INTO questions (id, test_id, question_text) VALUES (53, 8, 'I feel on guard or watchful in everyday situations.');
INSERT INTO questions (id, test_id, question_text) VALUES (54, 8, 'I am easily startled.');

-- Demo results for member (user id 2)
INSERT INTO results (id, user_id, test_id, score, interpretation) VALUES (1, 2, 1, 18, 'Mild');
INSERT INTO answers (user_id, question_id, value) VALUES (2, 1, 4);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 2, 3);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 3, 4);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 4, 3);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 5, 4);
INSERT INTO results (id, user_id, test_id, score, interpretation) VALUES (2, 2, 3, 12, 'Moderate');
INSERT INTO answers (user_id, question_id, value) VALUES (2, 11, 1);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 12, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 13, 1);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 14, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 15, 1);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 16, 1);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 17, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 18, 1);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 19, 1);
INSERT INTO results (id, user_id, test_id, score, interpretation) VALUES (3, 2, 4, 14, 'Moderate');
INSERT INTO answers (user_id, question_id, value) VALUES (2, 20, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 21, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 22, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 23, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 24, 1);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 25, 1);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 26, 2);
INSERT INTO results (id, user_id, test_id, score, interpretation) VALUES (4, 2, 5, 26, 'Moderate');
INSERT INTO answers (user_id, question_id, value) VALUES (2, 27, 4);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 28, 4);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 29, 3);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 30, 4);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 31, 3);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 32, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 33, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 34, 4);
INSERT INTO results (id, user_id, test_id, score, interpretation) VALUES (5, 2, 6, 17, 'Medium');
INSERT INTO answers (user_id, question_id, value) VALUES (2, 35, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 36, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 37, 3);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 38, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 39, 3);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 40, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 41, 2);
INSERT INTO answers (user_id, question_id, value) VALUES (2, 42, 3);

-- Reset AUTO_INCREMENT after explicit IDs
ALTER TABLE users AUTO_INCREMENT = 3;
ALTER TABLE test_categories AUTO_INCREMENT = 6;
ALTER TABLE tests AUTO_INCREMENT = 9;
ALTER TABLE questions AUTO_INCREMENT = 55;
ALTER TABLE results AUTO_INCREMENT = 6;
ALTER TABLE answers AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;
