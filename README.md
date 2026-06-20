# WellMate Backend (Laravel 12)

Anonymous health Q&A platform API — Sanctum token auth, Spatie roles (admin/doctor/user), MySQL.

## Status of this build

Backend is **feature-complete** for the MVP: Auth, Question/Answer, Article, Doctor directory, and Admin verification/dashboard endpoints are all implemented. The Next.js frontend is generated separately.

## Requirements (macOS)

- PHP 8.2+ — `brew install php`
- Composer — `brew install composer`
- MySQL — easiest via [XAMPP](https://www.apachefriends.org) or `brew install mysql`

## Quick Setup

```bash
chmod +x setup-mac.sh
./setup-mac.sh
```

This installs dependencies, creates `.env`, and generates the app key. Then:

```bash
# Create the database (adjust if your MySQL has a password)
mysql -u root -e "CREATE DATABASE wellmate;"

# Run migrations + seed dummy data
php artisan migrate --seed

# Start the server
php artisan serve
```

API base URL: `http://localhost:8000`
Health check: `http://localhost:8000/api/ping`

## Manual Setup (if you skip the script)

```bash
composer install
cp .env.example .env
php artisan key:generate
# edit .env DB_* values to match your MySQL setup
php artisan migrate --seed
php artisan serve
```

## Seeded Test Accounts

All passwords: `password`

| Role | Email | Notes |
|---|---|---|
| Admin | admin@wellmate.test | Full admin access |
| Doctor (verified) | elena.kovac@wellmate.test | Adolescent Psychiatrist — teen anxiety & body image |
| Doctor (verified) | johan.berg@wellmate.test | Clinical Psychologist — divorce & grief |
| Doctor (verified) | marta.novakova@wellmate.test | OB-GYN — pregnancy |
| Doctor (pending) | aiden.cole@wellmate.test | Dermatologist, not yet verified — use to demo the admin verification flow |
| User | teen16@wellmate.test | Anonymous handle: QuietMornings16 |
| User | teen15@wellmate.test | Anonymous handle: StillGrowing15 |
| User | divorce41@wellmate.test | Anonymous handle: NewBeginnings41 |
| User | divorce35@wellmate.test | Anonymous handle: FinallyFree35 |
| User | expecting@wellmate.test | Anonymous handle: ExpectingJoy |

Dummy data: 10 answered Q&A pairs (teen anxiety/body image, divorce recovery, pregnancy) across the three verified doctors, plus 2 fresh **pending** questions left unanswered on purpose — log in as a doctor, check their dashboard's pending queue, answer one, and watch it appear in the public stream. Also includes 5 published health articles.

## API Reference

Auth header for protected routes: `Authorization: Bearer {token}`

**Public**
- `POST /api/register` — `{ name, email, password, password_confirmation, role: "user"|"doctor", anonymous_handle?, specialization?, license_number?, bio? }` (specialization/license_number required if role=doctor)
- `POST /api/login` — `{ email, password }`
- `GET /api/questions` — query: `category`, `status`
- `GET /api/questions/{id}`
- `GET /api/articles` — query: `category`
- `GET /api/articles/{slug}`
- `GET /api/doctors`
- `GET /api/doctors/{id}`

**Authenticated (any role)**
- `POST /api/logout`
- `GET /api/me`
- `POST /api/questions` — `{ title, body, category?, is_anonymous? }`
- `PATCH /api/questions/{id}/answers/{answerId}/accept` — question owner only

**Doctor (verified only)**
- `POST /api/questions/{id}/answers` — `{ body }`
- `DELETE /api/answers/{id}`
- `GET /api/my-articles`
- `POST /api/articles` — `{ title, body, category?, cover_image?, is_published? }`
- `PUT /api/articles/{slug}`
- `DELETE /api/articles/{slug}`

**Admin**
- `GET /api/admin/dashboard/stats`
- `GET /api/admin/doctors/pending`
- `GET /api/admin/doctors/verified`
- `PATCH /api/admin/doctors/{id}/verify`
- `DELETE /api/admin/doctors/{id}/reject` — rejects application, demotes user to "user" role

Unverified doctors get a `403` with an explanatory message if they try to answer questions or post articles.

## Troubleshooting

- **"could not find driver" / SQLSTATE error**: install the MySQL PHP extension — `brew install php` includes it, but if missing run `pecl install pdo_mysql` or check `php -m | grep pdo_mysql`.
- **Access denied for user 'root'**: update `DB_USERNAME` / `DB_PASSWORD` in `.env` to match your local MySQL credentials.
- **Port 8000 already in use**: run `php artisan serve --port=8001` instead.