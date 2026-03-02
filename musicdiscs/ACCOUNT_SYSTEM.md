# Account System - Music Discs Application

## Overview
A complete role-based authentication system with three distinct user roles: **Admin**, **Seller**, and **User**.

## User Roles & Permissions

### 1. Admin
- **Full system access**
- Can view, create, edit, and delete all LP's
- Access to admin dashboard
- Can manage all users (future feature)
- System settings access

**Test Account:**
- Email: `admin@example.com`
- Password: `password`

### 2. Seller
- Can view all LP's
- Can create and edit LP's
- Access to seller dashboard
- View sales reports (future feature)
- Cannot delete LP's

**Test Account:**
- Email: `seller@example.com`
- Password: `password`

### 3. User
- Can view all LP's
- Read-only access
- Cannot create, edit, or delete LP's
- Access to user dashboard
- Can save favorites (future feature)

**Test Account:**
- Email: `user@example.com`
- Password: `password`

## Features Implemented

### Authentication
- ✅ User registration with role selection
- ✅ User login with remember me option
- ✅ Secure logout
- ✅ Password hashing
- ✅ Session management

### Authorization
- ✅ Role-based middleware (`CheckRole`)
- ✅ Route protection by role
- ✅ Dynamic navigation based on user role
- ✅ Role checking methods in User model

### User Interface
- ✅ Login page
- ✅ Registration page
- ✅ Main dashboard (shows user info and role)
- ✅ Admin dashboard
- ✅ Seller dashboard
- ✅ User dashboard
- ✅ Role-based navigation menu
- ✅ Flash messages for success/error notifications

## Database Schema

### Users Table
```php
- id (primary key)
- name (string)
- email (string, unique)
- password (hashed string)
- role (enum: 'admin', 'seller', 'user') - default: 'user'
- email_verified_at (timestamp, nullable)
- remember_token (string)
- created_at (timestamp)
- updated_at (timestamp)
```

## Routes

### Public Routes
- `GET /` - Welcome page
- `GET /login` - Login form
- `POST /login` - Process login
- `GET /register` - Registration form
- `POST /register` - Process registration

### Protected Routes (Authentication Required)
- `POST /logout` - Logout user
- `GET /dashboard` - Main dashboard (all roles)
- `GET /lps` - View all LP's (all roles)
- `GET /lps/{id}` - View single LP (all roles)

### Admin & Seller Routes
- `GET /lps/create` - Create LP form
- `POST /lps` - Store new LP
- `GET /lps/{id}/edit` - Edit LP form
- `PUT /lps/{id}` - Update LP

### Admin Only Routes
- `GET /dashboard/admin` - Admin panel
- `DELETE /lps/{id}` - Delete LP

### Seller Only Routes
- `GET /dashboard/seller` - Seller dashboard

### User Only Routes
- `GET /dashboard/user` - User dashboard

## User Model Methods

```php
// Check if user has admin role
$user->isAdmin(): bool

// Check if user has seller role
$user->isSeller(): bool

// Check if user has user role
$user->isUser(): bool

// Check if user has any of the given roles
$user->hasRole('admin'): bool
$user->hasRole(['admin', 'seller']): bool
```

## Middleware Usage

### In Routes
```php
// Single role
Route::middleware('role:admin')->group(function () {
    // Admin only routes
});

// Multiple roles
Route::middleware('role:admin,seller')->group(function () {
    // Admin and seller routes
});
```

### In Controllers
```php
public function __construct()
{
    $this->middleware('role:admin');
}
```

## Blade Directives

### Check Authentication
```blade
@auth
    <!-- User is logged in -->
@endauth

@guest
    <!-- User is not logged in -->
@endguest
```

### Check Roles in Views
```blade
@if(auth()->user()->isAdmin())
    <!-- Admin content -->
@endif

@if(auth()->user()->hasRole(['admin', 'seller']))
    <!-- Admin or seller content -->
@endif
```

## Setup Instructions

1. **Run migrations** (if not already run):
```bash
php artisan migrate
```

2. **Seed the database** with test users:
```bash
php artisan db:seed
```

3. **Access the application**:
- Visit `/register` to create a new account
- Or use the test accounts provided above

## Security Features

- ✅ Password hashing using bcrypt
- ✅ CSRF protection on all forms
- ✅ Session regeneration on login
- ✅ Session invalidation on logout
- ✅ Middleware protection for routes
- ✅ 403 error for unauthorized access
- ✅ Redirect to login for unauthenticated users

## Future Enhancements

- Email verification
- Password reset functionality
- User profile management
- Admin user management interface
- Seller inventory tracking
- User favorites/wishlist
- Activity logs
- Two-factor authentication
- Social login (Google, Facebook, etc.)

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   └── DashboardController.php
│   └── Middleware/
│       └── CheckRole.php
└── Models/
    └── User.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   └── 2026_02_27_124748_add_role_to_users_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/
└── views/
    ├── auth/
    │   ├── login.blade.php
    │   └── register.blade.php
    └── dashboard/
        ├── index.blade.php
        ├── admin.blade.php
        ├── seller.blade.php
        └── user.blade.php

routes/
└── web.php
```

## Testing

Test the system by:
1. Registering new users with different roles
2. Logging in with test accounts
3. Trying to access restricted routes
4. Testing LP creation/editing based on role
5. Verifying navigation menu changes per role

## Support

For issues or questions about the account system, please refer to Laravel's documentation:
- [Authentication](https://laravel.com/docs/authentication)
- [Authorization](https://laravel.com/docs/authorization)
- [Middleware](https://laravel.com/docs/middleware)
