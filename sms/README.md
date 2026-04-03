# Student Management System (SMS)
**PHP + MySQL | Admin & Student Portal**

1. Developed by: Utkarsha Warbhe.
2. College: Kavikulguru Institute of Technology and Science, Ramtek.
Year: 2024-25 (Mini-project)
Subject: Academic project.

## Quick Start (Mock Data — No Database Needed)

1. Install [XAMPP](https://www.apachefriends.org/) or [WAMP](https://www.wampserver.com/)
2. Copy the `sms/` folder into `htdocs/` (XAMPP) or `www/` (WAMP)
3. Open: `http://localhost/sms/`

That's it! The app runs with mock data stored in PHP sessions.

---

## Login Credentials

| Role    | ID       | Password  |
|---------|----------|-----------|
| Admin   | ADMIN001 | admin123  |
| Student | STU001   | pass123   |
| Student | STU002   | pass123   |
| Student | STU003   | pass123   |
| Student | STU004   | pass123   |
| Student | STU005   | pass123   |

---

## Features

### Admin Module
- Dashboard with live stats (students, notices, cancelled classes)
- **Students** — Add new student accounts, remove students, view all records
- **Notices** — Post notices (Exam / Holiday / General / Urgent), delete old ones
- **Schedules** — Add class periods, mark teachers absent/present, remove periods

### Student Module
- **Home** — Summary of latest notices and cancelled class alerts
- **Notices** — Browse all notices with category filter
- **Schedule** — View today's timetable; cancelled classes highlighted
- **Profile** — View personal details, change password

---

## Switch to Real MySQL Database

1. Start MySQL in XAMPP/WAMP
2. Import the schema:
   ```
   mysql -u root -p < schema.sql
   ```
3. Open `includes/config.php` and set:
   ```php
   define('DB_PASS', 'your_mysql_password');
   define('USE_MOCK_DATA', false);
   ```
4. Reload the site — it now reads from MySQL.

---

## Project Structure

```
sms/
├── index.php              ← Login page
├── logout.php
├── schema.sql             ← MySQL schema + seed data
├── css/
│   └── style.css
├── includes/
│   ├── config.php         ← DB config + mock data
│   ├── auth.php           ← Login / session helpers
│   ├── layout.php         ← Shared header + sidebar
│   └── layout_end.php     ← Shared footer + modal
├── admin/
│   ├── dashboard.php
│   ├── students.php
│   ├── notices.php
│   └── schedules.php
└── student/
    ├── home.php
    ├── notices.php
    ├── schedule.php
    └── profile.php
```

---

## OOP Concepts Used

| Concept       | Where                                          |
|---------------|------------------------------------------------|
| Encapsulation | `config.php` — mock store behind getter funcs |
| Abstraction   | `auth.php` — login details hidden in functions|
| Inheritance   | PHP sessions carry role-specific data          |
| Polymorphism  | Same layout.php renders differently per role   |

---

## Technologies

- **PHP 8.x** — Server-side logic, sessions, form handling
- **MySQL** — Persistent storage (when USE_MOCK_DATA = false)
- **JDBC equivalent** — PHP MySQLi with prepared statements
- **HTML5 + CSS3** — Responsive UI, DM Sans font
- **No frameworks** — Pure PHP, easy to understand and extend
