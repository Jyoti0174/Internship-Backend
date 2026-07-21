<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Employee Helpdesk / Ticket Management System

## 🌐 Live URLs

| | URL |
|---|---|
| **Frontend** | https://internship-frontened-project.vercel.app |
| **Backend API** | https://internship-backend-production.up.railway.app |

---

## 👥 Contributors

| Name | Role |
|---|---|
| Jyoti Pandey | Backend Developer |
| Harshita Gupta | Frontend Developer |

---

## 1. Project Overview

The **Employee Helpdesk & Ticket Management System** is a Laravel backend application that lets employees raise internal support tickets (IT, HR, Finance, and other departments) and lets managers and admins track, assign, and resolve them through a defined workflow.

**What this system does:**

- Employees register, log in, and raise support tickets tied to a department
- Managers and admins view, filter, search, sort, assign, and update tickets
- Every ticket moves through a status lifecycle: `open` → `in_progress` → `closed`
- Priority levels (`low`, `medium`, `high`) help triage urgent issues
- Department-wise categorization (Information Technology, Human Resources, Finance, Operations, Marketing, Legal)
- Comments and file attachments on tickets for back-and-forth communication
- Activity logs record who did what and when, for auditing
- Email notifications on ticket creation, assignment, and status changes
- A Reports module lets admins/managers filter tickets by department, status, priority, and date range, view the total matching count, and export the result as CSV or PDF
- Users can view/update their profile, change their password, and upload a profile photo

This project was built as part of an internship, focused on practical backend development: MVC architecture, RESTful API design, database migrations and seeders, role-based access control, file handling, and report generation.

---

## 2. Technology Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.2+ |
| Framework | Laravel 11 |
| Database | PostgreSQL 14+ |
| ORM | Eloquent |
| Auth | Laravel Sanctum (token-based) |
| API | RESTful JSON API |
| Views | Blade templates (used for a small internal test/reference UI, separate from the main Angular frontend) |
| File Storage | Laravel Storage (public disk) for profile photos |
| PDF Generation | Used for the Reports PDF export endpoint |
| Testing | PHPUnit (Laravel's default testing scaffold is included; project-specific test coverage was not a focus of this internship) |

**Project Structure:**
```
internship-backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Ticket, User, Auth, Department, Report, Comment, Attachment controllers
│   │   ├── Middleware/      # Role-based access control, Sanctum auth
│   │   └── Requests/        # Form validation rules (Store/Update Ticket, Assign Ticket, etc.)
│   ├── Models/               # User, Ticket, Department, Comment, Attachment, ActivityLog
│   ├── Helpers/               # NotificationHelper (email dispatch)
│   └── Traits/                 # ApiResponse (consistent success/error JSON shape)
├── database/
│   ├── migrations/          # All table schema definitions
│   └── seeders/                # DepartmentSeeder, UserSeeder, TicketSeeder
├── resources/views/
│   └── tickets/                  # Blade views (create, edit, index, show) — internal reference UI
├── routes/
│   ├── api.php                    # All REST API routes
│   └── web.php                  # Web routes for the Blade views
├── storage/app/public/profile-photos/  # Uploaded profile photos
├── tests/                        # Feature and unit tests (default Laravel scaffold)
├── .env.example
├── composer.json
└── README.md
```

---

## 3. Installation Guide

### Software Prerequisites

| Software | Minimum Version | Download |
|---|---|---|
| PHP | v8.2+ | https://www.php.net/downloads |
| Composer | v2.x+ | https://getcomposer.org/ |
| PostgreSQL | v14.x+ | https://www.postgresql.org/download/ |
| Git | v2.x+ | https://git-scm.com/ |

Verify your installations:
```bash
php -v
composer -V
psql --version
git --version
```

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/Jyoti0174/Internship-Backend.git
cd Internship-Backend
```

**2. Install dependencies**
```bash
composer install
```

**3. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Set up the database** — see [Database Setup](#4-database-setup) below.

**5. Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

**6. Start the server** — see [Running the Application](#6-running-the-application) below.

---

## 4. Database Setup

### PostgreSQL Setup

**1. Start PostgreSQL Service**

macOS:
```bash
brew services start postgresql@14
```

Ubuntu/Debian:
```bash
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

Windows: Open `services.msc` → start `postgresql-x64-14`

**2. Open PostgreSQL Shell**
```bash
psql -U postgres
```

**3. Create Database and User**
```sql
CREATE USER helpdesk_user WITH PASSWORD 'your_secure_password';

CREATE DATABASE helpdesk_dev OWNER helpdesk_user;
CREATE DATABASE helpdesk_test OWNER helpdesk_user;

GRANT ALL PRIVILEGES ON DATABASE helpdesk_dev TO helpdesk_user;
GRANT ALL PRIVILEGES ON DATABASE helpdesk_test TO helpdesk_user;

\q
```

**4. Verify Connection**
```bash
psql -U helpdesk_user -d helpdesk_dev -h localhost
```

### Environment Configuration

Update `.env` with your local values:
```env
APP_NAME="Employee Helpdesk System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=helpdesk_dev
DB_USERNAME=helpdesk_user
DB_PASSWORD=your_secure_password

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_FROM_ADDRESS=helpdesk@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
FILESYSTEM_DISK=public
```

> ⚠️ Never commit your `.env` file — it is already listed in `.gitignore`.
>
> Note: `MAIL_MAILER=log` writes emails to `storage/logs/laravel.log` instead of actually sending them, which is convenient for local development when no mail server (e.g. Mailpit/Mailtrap) is running.

### Migration Commands

```bash
php artisan migrate                 # Run all pending migrations
php artisan migrate:fresh           # Drop all tables and re-run
php artisan migrate:rollback        # Rollback last migration batch
php artisan migrate:status          # Check migration status
php artisan make:migration add_x_to_tickets_table   # Create a new migration
```

### Seeder Commands

```bash
php artisan db:seed                             # Run all seeders
php artisan db:seed --class=UserSeeder          # Run a specific seeder
php artisan migrate:fresh --seed                # Fresh migration + seed together
```

Seeders run in this order (see `DatabaseSeeder.php`): `DepartmentSeeder` → `UserSeeder` → `TicketSeeder`.

**Default Seeded Accounts (Development Only)** — all use the password `password`:

| Role | Name | Email |
|---|---|---|
| Admin | Super Admin | admin@helpdesk.com |
| Admin | HR Admin | hradmin@helpdesk.com |
| Manager | IT Manager | itmanager@helpdesk.com |
| Manager | HR Manager | hrmanager@helpdesk.com |
| Manager | Finance Manager | finmanager@helpdesk.com |
| Manager | Ops Manager | opsmanager@helpdesk.com |
| Employee | Rahul Sharma (and others) | rahul@example.com |

> ⚠️ These are development-only credentials. Change or remove them before deploying to any non-development environment.

The `TicketSeeder` also populates a realistic set of sample tickets across all departments and statuses, useful for testing filters, reports, and dashboard stats.

---

## 5. API Documentation

The complete API reference — every endpoint, HTTP method, required role, request body, and example success/error responses — is maintained separately in **`Ticket_API_Documentation.docx`** (included in this repository / shared with the project).

**API groups covered:**

| Group | Examples |
|---|---|
| Authentication | `POST /register`, `POST /login`, `POST /logout`, `GET /me` |
| User Profile | `GET /profile`, `PUT /profile`, `PUT /profile/password`, `POST /profile/photo` |
| Users (Admin) | `GET /users`, `POST /users`, `PUT /users/{id}`, `DELETE /users/{id}` |
| Departments | `GET /departments`, `POST /departments`, `PUT /departments/{id}`, `DELETE /departments/{id}` |
| Tickets | `GET /tickets` (search, filter, sort, paginate), `POST /tickets`, `PUT /tickets/{id}`, `DELETE /tickets/{id}`, `PUT /tickets/{id}/assign`, `PATCH /tickets/{id}/status` |
| Dashboard | `GET /tickets/stats`, `GET /tickets/stats/by-department`, `GET /tickets/recent` |
| Comments & Attachments | `GET/POST /tickets/{id}/comments`, `GET/POST /tickets/{id}/attachments` |
| Reports | `GET /reports` (filtered list + total count), `GET /reports/export` (CSV), `GET /reports/export-pdf` (PDF) |

All protected routes require a Sanctum Bearer token in the `Authorization` header. Most admin/manager-only routes are enforced via a `role:admin,manager` middleware group in `routes/api.php`.

---

## 6. Running the Application

Start the development server:
```bash
php artisan serve
```

API available at: **http://127.0.0.1:8000**

Run tests:
```bash
php artisan test
```

Clear all caches (useful after changing routes, config, or views):
```bash
php artisan optimize:clear
```

### Quick Start

```bash
git clone https://github.com/Jyoti0174/Internship-Backend.git
cd Internship-Backend
composer install
cp .env.example .env
php artisan key:generate
# Edit .env with your DB credentials
php artisan migrate:fresh --seed
php artisan serve
```

---

## 7. Project Features

- **Authentication** — registration, login, logout, Sanctum token-based API auth
- **Role-based access control** — admin, manager, and employee roles with different permissions
- **User profile management** — view/update profile, change password (with current-password check, strength rules, and confirmation match), upload/replace profile photo
- **Ticket management** — full CRUD, keyword search, department/status/priority filters, sorting, pagination
- **Ticket assignment workflow** — assign/unassign to a support agent, status updates with audit trail
- **Comments & attachments** — collaborate on a ticket and attach supporting files
- **Activity logs** — track changes made to a ticket over time
- **Department management** — list, create, update, delete departments; department dropdown support for ticket forms
- **Dashboard analytics** — total/open/in-progress/closed counts, tickets-by-department bar chart data, recent tickets
- **Email notifications** — sent on ticket creation, assignment, and status change (configurable per user)
- **Reports module** — filter by department, status, priority, and date range; view total matching records; export as CSV or PDF, both reflecting the currently applied filters

---

## Contributing

Thank you for considering contributing to this project! Please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability, please send an e-mail to the project maintainer. All security vulnerabilities will be promptly addressed.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).