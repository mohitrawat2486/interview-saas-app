# One-Way Interview SaaS — Laravel + MySQL

> A lightweight, production-ready platform for **asynchronous candidate video interviews**.  
> Admins and reviewers create interviews and invitations; candidates record answers in-browser; reviewers score and comment.

[![Read the Docs](https://img.shields.io/badge/Read%20the%20Docs-live-2C4?logo=readthedocs)](https://your-project.readthedocs.io/en/latest/)

---

## Table of Contents
- [About this Project](#about-this-project)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Testing Logins (Seeded)](#testing-logins-seeded)
- [Key Routes](#key-routes)
- [Ops & Security](#ops--security)

---

## About this Project
“Hireflix-style” one-way interviews let teams evaluate candidates on their own time. This app ships the core: interview builder, tokenized invitations, browser recording (no apps needed), and a reviewer console with per-question scoring. It’s designed as a SaaS foundation—clean Laravel code, Tailwind UI, and optional cloud storage/CDN for scale.

Badges:
- **Multi-role** (Admin/Reviewer/Candidate)
- **Secure Streaming** (optional private disk)
- **Lightweight Alpine.js UX**

---

## Features
- Admins/Reviewers: create interviews (title, description, defaults), manage questions (order, time/think limits, retakes).
- Invitations: tokenized links with optional expiry; candidates can start without creating an account (auto-provisioned).
- Candidate Recorder: in-browser `MediaRecorder` (WebM/MP4 where supported), per-question timer, configurable retakes, uploads.
- Reviewer Console: watch answers, score each question, leave comments, overall score/comment.
- Storage: public (MVP) or private disk + controller-gated streaming; S3/CloudFront ready.
- Clean code: Laravel MVC, Breeze auth, Tailwind/Blade, Alpine micro-interactions.

---

## Technologies Used
- **Laravel 11** (PHP 8.2+), Breeze (Blade) for auth & scaffolding  
- **MySQL 8** for relational data  
- **Tailwind CSS** UI + **Alpine.js** for interactive bits  
- **MediaRecorder API** for camera/mic capture in the browser  
- **Vite** for asset bundling  
- Optional: **S3 + CloudFront** with signed URLs, queues for post-processing

---

## Installation

**Requirements:** PHP 8.2+, Composer, Node 18+, MySQL 8 (or MariaDB 10.6+)

1) Create the Laravel app
```bash
composer create-project laravel/laravel interviewApp
cd interviewApp

2) Auth scaffolding (Breeze)

composer require laravel/breeze --dev
php artisan breeze:install blade
npm i
npm run build

3) Environment & storage

cp .env.example .env
php artisan key:generate
php artisan storage:link

4) Database & seed

php artisan migrate
php artisan db:seed --class=UserSeeder

5) Run 

php artisan serve

Note: Video files default to the public disk for a quick start. For production, switch to a private disk and stream via the auth-checked controller (see Ops & Security below).


| Role      | Email                                                 | Password   | Notes                          |
| --------- | ----------------------------------------------------- | ---------- | ------------------------------ |
| Admin     | [admin@example.com](mailto:admin@example.com)         | `12345678` | Full control                   |
| Reviewer  | [reviewer@example.com](mailto:reviewer@example.com)   | `12345678` | Review & score                 |
| Candidate | [candidate@example.com](mailto:candidate@example.com) | `12345678` | Usually enters via invite link |

Candidate Invitation Start Link (example):
http://127.0.0.1:8000/i/7efce4d3-3a03-4021-92a7-886b10d2d2d7


Key Routes

Admin / Reviewer

/admin/interviews — manage interviews

/review/submissions — review queue

Candidate Flow

/i/{token} — invitation landing → start

/s/{submission}/q/{order} — recorder per question

Secure Streaming

/answer/{answer}/stream — gated playback (auth required)

Ops & Security

1. Private videos: set FILESYSTEM_DISK=private, serve via controller (auth check) or S3 signed URLs.

2. Upload limits: tune upload_max_filesize, post_max_size, and web server client size.

3. HTTPS & Permissions: use HTTPS in production; restrict who can view submissions.

4. Scalability: offload media to S3 + CloudFront; enable queues for email/post-processing.

5. Compliance: define data retention & deletion policies; consider per-tenant buckets if multi-tenant.
