# Hireflix-Style One-Way Video Interview — Laravel + MySQL (MVP Pack)

This pack contains all the **app code**, **migrations**, **routes**, and **views** to turn a fresh Laravel project
into a one-way video interview app. It assumes **Laravel 11**, **PHP 8.2+**, **MySQL**, and **Breeze (Blade)** for auth.

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

## 3) Copy this pack into your project

Unzip this pack into your newly created project (overwrite when prompted):

```bash
# from the directory containing this zip
unzip -o hireflix-mvp-pack.zip -d hireflix
```

Alternatively, copy with rsync or your file explorer.

---

## 4) Register the role middleware alias

Open **app/Http/Kernel.php** and add this line to `$routeMiddleware`:

```php
'role' => \App\Http\Middleware\CheckRole::class,
```

---

## 5) Migrate + seed (creates admin/reviewer/candidate test users)

```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
```

Users created:
- admin@example.com / password (role: admin)
- reviewer@example.com / password (role: reviewer)
- candidate@example.com / password (role: candidate)

---

## 6) Run it

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
  