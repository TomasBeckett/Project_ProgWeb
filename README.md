# 💼 OpenKerja

**OpenKerja** is a web-based job portal built with **PHP** and **MySQL**. It connects companies (employers) who post job openings ("lowongan") with job seekers who can browse, search, and apply for those openings directly through the site.

## 👥 Group 1

| NIM | Name |
|---|---|
| 71230985 | Tomas Becket |
| 71230981 | Deo Dewanto |
| 71231041 | Revandra Talenta Mapuasate Patimang |

## ✨ Features

### For Job Seekers
- Browse job listings with live search/filtering by company, category (bidang), location, and job type — no page reload needed
- View full job details, including description, salary range, and company logo/banner
- Register and log in with an account
- Apply for a job ("pengajuan lamaran") by submitting personal info, CV, portfolio, and a cover letter, plus answering job-specific screening questions
- Prevents duplicate applications to the same listing

### For Employers ("Pengelola")
- Separate staff login (accounts using a `@staff.com` email are routed to the employer dashboard)
- Post new job listings with title, field, job type, salary range, location, description, screening questions, logo, and banner image
- Edit or manage existing job listings
- Applicant CVs and portfolios can be viewed as in-browser PDFs

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural, `mysqli`) |
| Database | MySQL / MariaDB |
| Frontend | HTML, CSS, JavaScript |
| File handling | Native PHP file uploads (logos, banners, CVs, portfolios as PDFs) |

## 📁 Project Structure

```
Project_ProgWeb-main/
├── Assets/
│   ├── Pic/                  # Images: banners, company logos, UI assets
│   └── css/                  # Stylesheets (web, detail, pengajuan, pengelola)
├── Pages/
│   ├── db.php                 # Database connection config
│   ├── home.php                # Landing page — job search & listing
│   ├── header.php               # Shared header (nav, login/profile dropdown)
│   ├── footer.php                # Shared footer
│   ├── detail.php                 # Job listing detail page
│   ├── pengajuan.php               # Job application form
│   ├── pengelola.php                # Employer dashboard
│   ├── addlowongan.php               # Add/edit job listing (employer)
│   ├── fetch_jobs.php                 # AJAX endpoint for filtered job search
│   ├── login_process.php               # Login handler
│   ├── register_process.php             # Registration handler
│   ├── logout.php                        # Logout handler
│   ├── pdf.php                            # In-browser PDF viewer (CV/portfolio)
│   └── uploads/                            # Uploaded logos, banners, CVs, portfolios
├── openkerja.sql              # Database schema & seed data
└── README.md
```

## 🗄️ Database Schema

The `openkerja` database consists of three main tables:

- **`users`** — accounts for both job seekers and employer staff (differentiated by `@staff.com` email domain)
- **`lowongan`** — job listings (title, field, type, salary, location, description, screening questions, logo, banner, owning company)
- **`pelamar`** — job applications (applicant info, CV, portfolio, cover letter, linked to a listing and a user)

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.x
- MySQL / MariaDB
- A local server stack such as **XAMPP**, **Laragon**, or **WAMP**

### 1. Clone the repository
```bash
git clone <this-repo-url>
```
Place the project folder inside your server's web root (e.g. `htdocs` for XAMPP).

### 2. Create the database
1. Start MySQL/MariaDB and open phpMyAdmin (or use the CLI).
2. Create a database named `openkerja`.
3. Import the provided schema and seed data:
```bash
mysql -u root -p openkerja < openkerja.sql
```

### 3. Configure the database connection
Check `Pages/db.php` and adjust the credentials if needed (defaults to `root` with no password on `localhost`):
```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "openkerja";
```

### 4. Run the app
Start your local server (e.g. Apache via XAMPP) and open:
```
http://localhost/Project_ProgWeb-main/Pages/home.php
```

## 🔑 Login Notes

- Accounts with an email ending in **`@staff.com`** are treated as **employers** and redirected to the `pengelola.php` dashboard after login.
- All other accounts are treated as regular **job seekers**.

## 📌 Notes

- Uploaded files (banners, logos, CVs, portfolios) are stored under `Pages/uploads/` — the seed data in `openkerja.sql` references sample files already included in this folder.
- Passwords are stored using PHP's `password_hash()` and verified with `password_verify()`.
- This project was built as coursework for a Web Programming course.
