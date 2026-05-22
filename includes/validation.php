<?php

declare(strict_types=1);

function validate_test_input(string $title, string $description, int $categoryId = 0): array
{
    $errors = [];

    $title = trim($title);
    $description = trim($description);

    if ($categoryId < 1) {
        $errors[] = 'Please select a test category.';
    }

    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (mb_strlen($title) < 3) {
        $errors[] = 'Title must be at least 3 characters.';
    } elseif (mb_strlen($title) > 255) {
        $errors[] = 'Title must not exceed 255 characters.';
    }

    if (mb_strlen($description) > 5000) {
        $errors[] = 'Description must not exceed 5000 characters.';
    }

    return $errors;
}

function validate_category_input(string $name, string $description, int $sortOrder): array
{
    $errors = [];

    $name = trim($name);
    $description = trim($description);

    if ($name === '') {
        $errors[] = 'Category name is required.';
    } elseif (mb_strlen($name) < 2) {
        $errors[] = 'Category name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 255) {
        $errors[] = 'Category name must not exceed 255 characters.';
    }

    if (mb_strlen($description) > 2000) {
        $errors[] = 'Description must not exceed 2000 characters.';
    }

    if ($sortOrder < 0 || $sortOrder > 999) {
        $errors[] = 'Sort order must be between 0 and 999.';
    }

    return $errors;
}

function validate_question_input(int $testId, string $questionText): array
{
    $errors = [];

    if ($testId < 1) {
        $errors[] = 'Please select a valid test.';
    }

    $questionText = trim($questionText);

    if ($questionText === '') {
        $errors[] = 'Question text is required.';
    } elseif (mb_strlen($questionText) < 3) {
        $errors[] = 'Question must be at least 3 characters.';
    } elseif (mb_strlen($questionText) > 5000) {
        $errors[] = 'Question must not exceed 5000 characters.';
    }

    return $errors;
}

function validate_profile_input(
    string $name,
    string $email,
    string $currentPassword,
    string $newPassword,
    string $confirmPassword
): array {
    $errors = [];

    $name = trim($name);
    $email = trim($email);

    if ($name === '') {
        $errors[] = 'Name is required.';
    } elseif (mb_strlen($name) < 2) {
        $errors[] = 'Name must be at least 2 characters.';
    } elseif (mb_strlen($name) > 100) {
        $errors[] = 'Name must not exceed 100 characters.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } elseif (mb_strlen($email) > 150) {
        $errors[] = 'Email must not exceed 150 characters.';
    }

    if ($currentPassword === '') {
        $errors[] = 'Current password is required to save changes.';
    }

    if ($newPassword !== '' || $confirmPassword !== '') {
        if ($newPassword === '') {
            $errors[] = 'Please enter a new password or leave both new password fields empty.';
        } elseif (mb_strlen($newPassword) < 6) {
            $errors[] = 'New password must be at least 6 characters.';
        }

        if ($confirmPassword === '') {
            $errors[] = 'Please confirm your new password.';
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = 'Password confirmation does not match.';
        }
    }

    return $errors;
}
