# ISVuln — Security Audit Management

A full-stack security audit management platform built from a bare AlmaLinux server to a production-style application. ISVuln helps security teams log, triage, and remediate vulnerability findings through a complete lifecycle — with role-based access control, threat intelligence integrations, and a forensic audit trail enforced from the ground up.

Built as a hands-on learning project bridging full-stack web development and cybersecurity, by a Cybersecurity graduate.

**Live demo:** [isvuln.isdevelopers.com.br](https://isvuln.isdevelopers.com.br)

---

## Features

### Vulnerability Management
- Full CRUD lifecycle for security findings: title, description, CVSS-aligned severity (Critical/High/Medium/Low/Info), remediation status (Open/In Progress/Resolved/Accepted Risk), target, and CVE reference
- Evidence file attachments per finding, with secure upload handling
- Proof-of-concept notes field for reproducible findings

### Access Control
- Role-based access control via Spatie Laravel Permission — **Admin**, **Analyst**, and **Viewer** roles
- Ownership policies enforced at the route, controller, and query level — analysts can only edit their own findings
- IDOR (Insecure Direct Object Reference) protection verified through self-conducted penetration testing
- Resource enumeration mitigated by normalizing 404 responses to 403 on protected routes

### Threat Intelligence Integrations
- **NVD (National Vulnerability Database) API** — auto-fills description and CVSS-derived severity from a CVE ID lookup
- **VirusTotal API** — scans a finding's target URL against 90+ security vendors and displays an aggregated threat verdict

### Audit Logging
- Append-only audit trail capturing every create, update, and delete across audited models, plus authentication events (login, logout, failed login)
- Records the full before/after diff of changed fields, actor, IP address, and user agent
- Tamper-resistant by design: application-level append-only enforcement, combined with database-level revocation of `UPDATE`/`DELETE` privileges on the audit table — verified to hold even against direct database access
- Full design rationale and threat model documented in [`docs/audit-logging.md`](docs/audit-logging.md)

### Secure File Uploads
- Evidence files stored outside the web root with randomized filenames
- Server-side MIME type verification (not client-reported) against a strict whitelist
- Files served exclusively through an authenticated download route — never linked directly
- Hardened after a deliberate self-pentest that confirmed unauthenticated remote code execution in the initial naive implementation

### Notifications
- Email notifications on finding assignment and reassignment, themed to match the application
- Delivered via authenticated SMTP

### Localization
- Full English / Brazilian Portuguese (PT-BR) localization across the public site and authenticated application
- Severity and status values are translated for display only — never altered at the data layer

### Dashboard
- Live severity and remediation metrics
- Interactive ApexCharts donut visualization of findings by severity
- Recent findings feed, scoped to what the logged-in user is authorized to see

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Database | MariaDB 10.11 |
| Frontend | Tailwind CSS, Alpine.js, ApexCharts |
| Auth & Access | Laravel Breeze, Spatie Laravel Permission |
| Server | AlmaLinux 10, Nginx, PHP-FPM, SELinux (enforcing) |
| TLS | Let's Encrypt via Certbot |
| Mail | SMTP |

---

## Security Practices Demonstrated

- CSRF protection on all state-changing requests
- Mass assignment protection via explicit `$fillable` definitions
- Least-privilege database accounts — the application's MariaDB user cannot modify or delete audit records, even under SQL injection
- SELinux enforcing mode with scoped contexts (read-only vs. read-write) per directory
- Server-side validation independent of client-supplied data (file types, form inputs)
- Self-conducted security assessments on file upload and access control, with documented findings and remediation

---

Built by [Gabriel Crepaldi](https://github.com/gabrielcrepaldi) — Cybersecurity graduate USF 2026
