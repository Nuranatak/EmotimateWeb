# Database files

| File | Purpose |
|------|---------|
| `emotimate.sql` | **Schema only** — empty tables + 5 default categories (fresh install) |
| `emotimate_with_demo.sql` | **Full demo export** — schema + sample users, tests, questions, results (recommended for presentation) |
| `migrate_categories.sql` | Optional migration for older databases without categories |
| `clear_tests.sql` | Deletes all tests/questions/results; keeps users and categories |
| `seed_admin.sql` | Reference only — use `setup/create_admin.php` instead |

## Demo import (recommended for submission)

1. Create database `emotimate` in phpMyAdmin (collation: `utf8mb4_unicode_ci`).
2. Import **`emotimate_with_demo.sql`** (Import tab → choose file → Go).
3. Optional: run `http://localhost/emotimate/setup/seed_demo_data.php` to refresh Likert instructions text on all tests (idempotent).

## Demo accounts (after `emotimate_with_demo.sql`)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@emotimate.com` | `admin123` |
| Member | `sarah.demo@emotimate.com` | `user123` |

## Demo content included

- **8 psychological tests** across all 5 categories
- **54 questions** (Likert 1–5)
- **5 completed results** + answer history for the demo member
- Highlights: **Depression (PHQ-9)**, **Anxiety (GAD-7)**, **Personality Style Inventory**, schema scales, attention & trauma screens

## Existing database (keep your data)

Use **`setup/seed_demo_data.php`** only — it skips duplicates and does not delete existing rows.

## Charset

All exports use `utf8mb4` for full Unicode support (English + special characters in question text).
