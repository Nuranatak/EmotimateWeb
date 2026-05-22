# Setup utilities

Run these **once** in a local/XAMPP environment after importing `database/emotimate.sql`.

| Script | Description |
|--------|-------------|
| `create_admin.php` | Create or repair default admin (`admin@emotimate.com` / `admin123`). Locks after success via `.admin_seeded.lock`. |
| `install_admin_now.php` | Quick alternative admin installer (same credentials). |
| `clear_tests.php` | Remove all tests, questions, results, and answers; **keeps categories**. |
| `seed_demo_data.php` | **Demo content** — users, tests, questions, sample results (idempotent, safe on existing DB). |
| `build_demo_export.py` | Regenerates `database/emotimate_with_demo.sql` from definitions. |

**Security:** Set `SETUP_ENABLED` to `false` in `config/app.local.php` before publishing, or delete the `setup/` folder from the server. Scripts return HTTP 403 when disabled.

## Quick demo setup

```
1. Import database/emotimate_with_demo.sql in phpMyAdmin
2. Open http://localhost/emotimate/setup/seed_demo_data.php (fills instructions text)
3. Login as sarah.demo@emotimate.com / user123 or admin@emotimate.com / admin123
```
