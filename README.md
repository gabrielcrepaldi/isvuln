# ISVuln — Security Audit Management

A full-stack security audit management SaaS built with Laravel 13, PHP 8.3, MariaDB, and Tailwind CSS. Developed as a learning project to bridge web development and cybersecurity skills.

## Features

- **Role-based access control** — Admin, Analyst, Viewer, and Demo roles enforced at route, policy, and query level
- **Vulnerability CRUD** — Log findings with severity (CVSS-based), status lifecycle, CVE references, and proof-of-concept notes
- **User management** — Admin-controlled user creation and role assignment
- **Bilingual UI** — Full EN/PT-BR localization
- **Dashboard** — Live severity stats, remediation metrics, and ApexCharts donut visualization
- **HTTPS** — Let's Encrypt via Certbot on AlmaLinux 10

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Database | MariaDB 10.11 |
| Frontend | Tailwind CSS, Alpine.js, ApexCharts |
| Server | Nginx, AlmaLinux 10, SELinux |
| Auth | Laravel Breeze + Spatie Laravel Permission |

## Security Concepts Implemented

- CSRF protection on all forms
- Mass assignment protection via `$fillable`
- Ownership policies preventing IDOR
- Role middleware on all authenticated routes
- SELinux enforcing mode on the server
- Input validation with server-side rules

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
```

## Live Demo

[dev4.isdevelopers.com.br](https://dev4.isdevelopers.com.br)

---

Built by [Gabriel Crepaldi](https://github.com/gabrielcrepaldi) — Cybersecurity graduate (University of South Florida).
