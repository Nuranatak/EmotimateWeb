# Configuration

Copy the example templates to local overrides (both are gitignored):

| Template | Local override | Purpose |
|----------|----------------|---------|
| `app.example.php` | `app.local.php` | Base URL, debug mode, setup scripts |
| `database.example.php` | `database.local.php` | MySQL credentials |

Load order:

1. `includes/bootstrap.php` — app settings + error handling + URL helpers
2. `config/database.php` — mysqli connection using credentials array

## Quick local overrides

**`config/app.local.php`** (XAMPP subdirectory):

```php
<?php
declare(strict_types=1);
define('APP_BASE', '/emotimate');
define('APP_DEBUG', false);
define('APP_ENV', 'local');
define('SETUP_ENABLED', true);
```

**`config/app.local.php`** (production / domain root):

```php
<?php
declare(strict_types=1);
define('APP_BASE', '');
define('APP_DEBUG', false);
define('APP_ENV', 'production');
define('SETUP_ENABLED', false);
```

**`config/database.local.php`**:

```php
<?php
declare(strict_types=1);
return [
    'host'     => 'localhost',
    'dbname'   => 'your_db_name',
    'username' => 'your_db_user',
    'password' => 'your_db_password',
    'charset'  => 'utf8mb4',
];
```
