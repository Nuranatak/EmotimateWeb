-- Removes all tests and related data. Keeps test_categories and users.
-- Run in phpMyAdmin on database "emotimate".

DELETE FROM tests;

-- Cascades: questions, results; answers removed with questions.
