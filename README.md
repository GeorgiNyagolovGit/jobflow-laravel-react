# JobApp

A full-stack job marketplace that connects candidates with employers. Candidates can browse roles, save favourites and track applications; employers can create a company, publish job listings and manage applicants. Administrators have a dedicated area for managing users, jobs and companies.

## Features

- Browse, search and filter job listings and companies
- Register, sign in, reset a password and authenticate with Google
- Create and manage a candidate profile
- Save favourite jobs and submit applications
- Employer workspace for company details, job listings and applicant reviews
- Role-based access control for candidates, employers and administrators
- Admin dashboard for users, jobs and company moderation

## Tech stack

- **Frontend:** React 19, TypeScript, Vite, React Router and Axios
- **Backend:** Laravel 13, PHP 8.3 and Laravel Socialite
- **Database:** PostgreSQL
- **Authentication:** Session-based authentication, role middleware and Google OAuth

## Project structure

```text
job-app/
├── front-end/  # React single-page application
└── back-end/   # Laravel API and server application
```

## Run locally

### Prerequisites

- PHP 8.3 or later
- Composer
- Node.js 20 or later and npm
- PostgreSQL

### Backend

```bash
cd back-end
composer install
cp .env.example .env
php artisan key:generate
```

Configure the PostgreSQL values in `.env`, then run the migrations and start Laravel:

```bash
php artisan migrate
php artisan serve
```

The backend will be available at `http://localhost:8000`.

### Frontend

In a second terminal:

```bash
cd front-end
npm install
npm run dev
```

The frontend will be available at `http://localhost:5173` and sends requests to the Laravel backend.

## Useful commands

| Location | Command | Purpose |
| --- | --- | --- |
| `front-end` | `npm run build` | Create a production frontend build |
| `front-end` | `npm run lint` | Run frontend linting |
| `back-end` | `php artisan test` | Run Laravel tests |
| `back-end` | `composer lint` | Format PHP code with Laravel Pint |

## Environment variables

The backend `.env.example` contains the required defaults. Set the database credentials and, if Google sign-in is enabled, provide:

```text
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

Never commit `.env` files or authentication credentials.
