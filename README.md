# CodegisoftNet Backend API

A Laravel 12 REST API for managing users and courses.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan serve
```

## API Endpoints

### Authentication

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/api/register` | Register new user | No |
| POST | `/api/login` | Login user | No |
| POST | `/api/logout` | Logout user | Yes |

### Users

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/users` | Get all users (optional: `?role=mentor\|student\|admin`) | No |
| POST | `/api/users` | Create user | Admin |
| GET | `/api/users/{id}` | Get user by ID | No |
| PUT | `/api/users/{id}` | Update user | Admin |
| DELETE | `/api/users/{id}` | Delete user | Admin |

### Courses

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/api/courses` | Get all courses | No |
| POST | `/api/courses` | Create course | Admin |
| GET | `/api/courses/{id}` | Get course by ID | No |
| PUT | `/api/courses/{id}` | Update course | Admin |
| DELETE | `/api/courses/{id}` | Delete course | Admin |

## Request Examples

### Login
```json
POST /api/login
{
  "email": "admin@codegisoft.com",
  "password": "admin123",
  "role": "admin"
}
```

### Create User (Admin)
```json
POST /api/users
Authorization: Bearer <token>
{
  "name": "John Doe",
  "email": "john@test.com",
  "password": "password123",
  "role": "student",
  "status": "active"
}
```

### Create Course (Admin)
```json
POST /api/courses
Authorization: Bearer <token>
{
  "title": "React Native",
  "duration": "8 weeks",
  "level": "Intermediate",
  "price": 199.99,
  "description": "Learn React Native",
  "start_date": "2026-03-01",
  "end_date": "2026-05-01"
}
```

## Response Format

**Success:**
```json
{
  "success": true,
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message"
}
```

## Default Users (Seeded)

| Email | Password | Role |
|-------|----------|------|
| admin@codegisoft.com | admin123 | admin |
| superadmin@codegisoft.com | superadmin123 | admin |
| mike.rodriguez@mentor.codegisoft.com | mentor123 | mentor |
| lisa.wang@mentor.codegisoft.com | mentor123 | mentor |
| alex.johnson@student.codegisoft.com | student123 | student |
| sarah.chen@student.codegisoft.com | student123 | student |

## License

MIT
