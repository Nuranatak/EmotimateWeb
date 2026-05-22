# EMOTIMATE

## Web-Based Psychological Testing Platform

EMOTIMATE is a university Web Programming final project that delivers structured psychological self-assessments using Likert-scale questionnaires, automated scoring, and detailed result interpretations.

The platform provides separate member and administrator panels with dashboards, Chart.js analytics, and full content management.

---

## Features

### Member Features
- Registration and secure login
- Personal dashboard with statistics and charts
- Browse psychological tests by category
- Likert-scale test interface
- Instant scoring and interpretation
- Profile management
- Test history tracking

### Admin Features
- Separate admin authentication
- Dashboard analytics
- CRUD operations for:
  - Categories
  - Tests
  - Questions
- View all member results
- Responsive admin sidebar

### Platform Features
- Mobile-responsive UI
- CSRF protection
- Flash messages
- Empty state handling
- Secure session management

---

## Technologies

| Layer | Technology |
|---|---|
| Backend | PHP 8+ (procedural), MySQLi |
| Database | MySQL / MariaDB |
| Frontend | HTML5, Bootstrap 5.3, Bootstrap Icons |
| Charts | Chart.js 4 |
| Server | Apache (XAMPP recommended) |
| Security | Prepared statements, CSRF, password hashing, XSS escaping |

---

## Installation

### Requirements

- XAMPP (Apache + PHP + MySQL) or equivalent
- PHP 8+
- MySQL / MariaDB
- Modern web browser

### Setup

1. Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/emotimate.git
cd emotimate
```

2. Place the project inside your web root

Example (XAMPP):

```text
C:\xampp\htdocs\emotimate\
```

3. Create local configuration files

```bash
copy config\app.example.php config\app.local.php
copy config\database.example.php config\database.local.php
```

4. Configure database credentials in:

```text
config/database.local.php
```

5. Create a database named:

```text
emotimate
```

6. Import:

```text
database/emotimate.sql
```

or demo database:

```text
database/emotimate_with_demo.sql
```

7. Start Apache and MySQL from XAMPP

8. Create the administrator account

```text
http://localhost/emotimate/setup/create_admin.php
```

9. Open the application

```text
http://localhost/emotimate/
```

---

## Demo Accounts

| Role | Email | Password |
|---|---|---|
| Admin | admin@emotimate.com | admin123 |
| Demo User | sarah.demo@emotimate.com | user123 |

---

## Project Structure

```text
emotimate/
├── actions/
├── admin/
├── assets/
├── auth/
├── config/
├── database/
├── includes/
├── setup/
├── tests/
├── user/
├── index.php
└── README.md
```

---

## Security Features

- Password hashing with `password_hash()`
- Prepared statements
- CSRF token validation
- Session regeneration on login
- XSS protection with `htmlspecialchars()`
- Role-based access control
- Secure session cookies

---

## Screenshots

Add screenshots inside:

```text
assets/screenshots/
```

Suggested files:

- `landing.png`
- `user-dashboard.png`
- `take-test.png`
- `result.png`
- `admin-dashboard.png`

Example usage:

```markdown
![Landing Page](assets/screenshots/landing.png)
```

---

## Future Improvements

- Email verification
- Password reset
- PDF export
- Multi-language support
- REST API integration
- Automated testing

---

## Author

**Nuran Atak**  
Web Programming Final Project   
**Institution:** [Universiteti Politeknik i Tiranës]  
**Year:** 2026

---

## License

This project was developed for academic purposes as part of a university coursework submission.
