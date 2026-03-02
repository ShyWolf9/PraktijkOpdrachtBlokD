# Quick Start Guide - Account System

## Test Accounts

### Admin Account
- **Email:** admin@example.com
- **Password:** password
- **Access:** Full system control, can manage all LP's and users

### Seller Account
- **Email:** seller@example.com
- **Password:** password  
- **Access:** Can create and edit LP's

### User Account
- **Email:** user@example.com
- **Password:** password
- **Access:** Can view LP's only

## Quick Access URLs

- **Login:** http://localhost/login
- **Register:** http://localhost/register
- **Dashboard:** http://localhost/dashboard
- **LP's:** http://localhost/lps

## How to Use

### 1. Login
1. Go to `/login`
2. Enter email and password
3. Click "Login"
4. You'll be redirected to your dashboard

### 2. Register New Account
1. Go to `/register`
2. Fill in: Name, Email, Password, Confirm Password
3. Select Role: User, Seller, or Admin
4. Click "Register"
5. You'll be automatically logged in

### 3. Role-Specific Features

#### As Admin:
- Access admin dashboard at `/dashboard/admin`
- Create, edit, and delete LP's
- View all system features
- Manage users (interface to be added)

#### As Seller:
- Access seller dashboard at `/dashboard/seller`
- Create and edit LP's
- Cannot delete LP's

#### As User:
- Access user dashboard at `/dashboard/user`
- Browse and view LP's
- Cannot modify LP's

## Navigation Tips

- The top navigation menu changes based on your role
- Click your name in the top right to see the logout button
- Unauthorized access attempts will show a 403 error

## Development Commands

```bash
# Run migrations
php artisan migrate

# Seed database with test users
php artisan db:seed

# Start development server
php artisan serve

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## File Locations

- **Routes:** `routes/web.php`
- **Controllers:** `app/Http/Controllers/AuthController.php` & `DashboardController.php`
- **Views:** `resources/views/auth/` & `resources/views/dashboard/`
- **Middleware:** `app/Http/Middleware/CheckRole.php`
- **User Model:** `app/Models/User.php`

## Security Features

✅ Passwords are hashed with bcrypt
✅ CSRF protection on all forms
✅ Middleware protects routes by role
✅ Session-based authentication
✅ Remember me functionality

Enjoy your new account system!
