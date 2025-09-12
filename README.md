# Hireflix-Style One-Way Video Interview — Laravel + MySQL (MVP Pack)

# Hireflix-Style One-Way Video Interview — Laravel + MySQL (MVP)

> A lightweight, production-ready platform for **asynchronous (one-way) video interviews** built with **Laravel 11** + **MySQL**.  
> Roles: **Admin** (manage), **Reviewer** (score), **Candidate** (record in-browser).

[![Docs](https://img.shields.io/badge/Read%20the%20Docs-live-2C4?logo=readthedocs)](https://YOUR-PROJECT.readthedocs.io/en/latest/)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777bb4)
![Laravel](https://img.shields.io/badge/Laravel-11-red)
![License](https://img.shields.io/badge/license-MIT-green)

---

## Table of Contents
- [About the Product](#about-the-product)
- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Installation](#installation)
- [Testing Logins](#testing-logins)
- [Candidate Flow & URLs](#candidate-flow--urls)
- [Environment Configuration](#environment-configuration)
- [Common Routes](#common-routes)
- [Troubleshooting](#troubleshooting)
- [Security & Ops Notes](#security--ops-notes)
- [Roadmap Ideas](#roadmap-ideas)
- [Contributing](#contributing)
- [License](#license)

---

## About the Product
This app delivers the **core workflow** of a one-way interview SaaS:
- **Admins** create interviews, add questions, and send tokenized invitations.
- **Candidates** join via a secure link and record answers **in the browser** (no app installs).
- **Reviewers** watch submissions and leave **per-question scores** and **overall comments**.

It’s intentionally minimal and clean so you can extend it into a full SaaS (payments, multi-tenant, analytics).

---

## Key Features
- **Interview builder:** title, description, per-question settings (order, time limit, thinking time, retakes).
- **Invitations:** tokenized candidate links with optional expiry.
- **In-browser recorder:** uses the **MediaRecorder API** (WebM; MP4 where supported), timer, optional retakes.
- **Review console:** watch answers, score per question, add comments, overall score/comment.
- **Storage:** public (MVP) or **private disk + secure streaming** controller; S3/CloudFront-ready.
- **Clean UX:** Tailwind UI + Alpine.js enhancements.

---

## Tech Stack
- **Backend:** Laravel 11 (PHP 8.2+), Eloquent, Breeze (Blade)
- **DB:** MySQL 8 (MariaDB 10.6+ also works)
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Vite
- **Media:** MediaRecorder API (WebM/Opus; Safari 17+ supports WebM/MP4)
- **Optional:** S3 + CloudFront (signed URLs), Queues for post-processing/email

---

## Architecture
```mermaid
erDiagram
  users ||--o{ interviews : "created_by"
  users ||--o{ submissions : "candidate_id"
  users ||--o{ reviews : "reviewer_id"

  interviews ||--o{ questions : contains
  interviews ||--o{ invitations : invites
  interviews ||--o{ submissions : has

  submissions ||--o{ answers : includes
  submissions ||--o{ reviews : reviewed_by

  questions ||--o{ answers : answered
  reviews ||--o{ review_items : details
  questions ||--o{ review_items : scored

---

## 0) Create a fresh Laravel project

```bash
composer create-project laravel/laravel hireflix
cd hireflix
```

> If you're using Sail or Valet, adapt accordingly.

---

## 1) Install Breeze (Blade) auth scaffolding

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm i
npm run build
```

---

## 2) Configure DB and storage

Edit `.env` to set your MySQL credentials, then:

```bash
php artisan key:generate
php artisan storage:link
```

> For MVP, we store videos on the **public** disk for quick streaming.
> See the notes below for switching to a private disk + signed streaming.


---

## 3) Migrate + seed (creates admin/reviewer/candidate test users)

```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```

Users created:
- admin@example.com / password (role: admin)
- reviewer@example.com / password (role: reviewer)
- candidate@example.com / password (role: candidate)

---

## 4) Run it

```bash
php artisan serve
```

- Login as **admin** or **reviewer** and go to `/dashboard`.
- Create an interview, add questions, create an invitation.
- Use the invitation link to record as a candidate (no login required for MVP).
- As a reviewer, go to `/review/submissions` to score and leave comments.

---

## Notes / Options

- **Public vs Private videos**: For quick MVP, videos are stored on the public disk. To harden:
  - Set `FILESYSTEM_DISK=private` and change the `VideoStreamController` and upload disk to `private`.
  - Keep the `<video src="{ route('answer.stream', $ans) }">` pattern so access checks run server-side.
- **Large uploads**: Bump PHP/server limits as needed (`upload_max_filesize`, `post_max_size`, Nginx/Apache client size).
- **Retakes & limits**: Enforced per-question; the views already track `retake_number`.
- **Thinking time**: Field exists (`thinking_time_seconds`), you can add a countdown overlay before recording starts.

Enjoy!
  