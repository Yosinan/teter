# Multi-User Task Management API with Role-Based Access

## Introduction
This project is a Multi-User Task Management API with role-based access control. It demonstrates authentication, API development, and authorization using Laravel and React/Next.

## Features
- **User Authentication**: Registration and login using Laravel Sanctum.
- **Role-Based Access Control**: Two roles - Admin and User.
- **Task Management**:
  - Admin can create, update, delete, and view all tasks.
  - Users can create, update, and delete their own tasks.
- **Task Model**:
  - `id` (auto-increment)
  - `user_id` (foreign key to users table)
  - `title` (string, required)
  - `description` (text, optional)
  - `status` (enum: pending, completed, in_progress, default: pending)
  - `priority` (enum: low, medium, high, default: medium)
  - `deadline` (date, required)
  - `created_at` and `updated_at`

## API Endpoints

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Login and receive an authentication token

### Task Management
- `POST /api/tasks` - Any authenticated user can create a task.
- `GET /api/tasks` - Admin can see all tasks; Users can only see their own.
- `PUT /api/tasks/{id}` - Admin can update any task; Users can update their own.
- `DELETE /api/tasks/{id}` - Admin can delete any task; Users can delete their own.

## Installation

### Backend (Laravel API)
1. Clone the repository:
   ```sh
   git clone https://github.com/yosinan/teter.git
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Set up the environment file:
   ```sh
   cp .env.example .env
   ```
   Update database credentials in the `.env` file.
4. Generate application key:
   ```sh
   php artisan key:generate
   ```
5. Run database migrations:
   ```sh
   php artisan migrate --seed
   ```
6. Start the development server:
   ```sh
   php artisan serve
   ```

<!-- ### Frontend (React App)
1. Navigate to the frontend directory:
   ```sh
   cd client
   ```
2. Install dependencies:
   ```sh
   npm install
   ```
3. Start the development server:
   ```sh
   npm start
   ``` -->

## Usage
1. Register a new user via `/api/register`.
2. Login via `/api/login` to receive an authentication token.
3. Use the token in requests to create, update, delete, and view tasks based on role permissions.

## Technologies Used
- **Backend**: Laravel, Sanctum, Spatie, MySQL
- **Frontend**: React, Axios, React Router
- **Authentication**: Laravel Sanctum

## License
This project is open-source under the MIT License.

---
For any issues or contributions, feel free to open a pull request or an issue.

