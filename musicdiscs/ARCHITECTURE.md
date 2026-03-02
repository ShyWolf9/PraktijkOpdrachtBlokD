# System Architecture

## Role-Based Access Control Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    MUSIC DISCS APPLICATION                    │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                     PUBLIC ROUTES                             │
│  - /               (Welcome Page)                             │
│  - /login          (Login Form)                               │
│  - /register       (Registration Form)                        │
└─────────────────────────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────┐
│                  AUTHENTICATION                               │
│              AuthController@login                             │
│              AuthController@register                          │
└─────────────────────────────────────────────────────────────┘
                             │
                             ▼
┌─────────────────────────────────────────────────────────────┐
│              ROLE ASSIGNMENT (User Model)                     │
│                                                               │
│    ┌─────────────┐  ┌─────────────┐  ┌─────────────┐       │
│    │    ADMIN    │  │   SELLER    │  │    USER     │       │
│    └─────────────┘  └─────────────┘  └─────────────┘       │
└─────────────────────────────────────────────────────────────┘
         │                   │                   │
         ▼                   ▼                   ▼
┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
│  ADMIN ROUTES   │  │  SELLER ROUTES  │  │  USER ROUTES    │
│  middleware:    │  │  middleware:    │  │  middleware:    │
│  role:admin     │  │  role:seller    │  │  role:user      │
└─────────────────┘  └─────────────────┘  └─────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    PERMISSION MATRIX                          │
├─────────────────┬─────────┬─────────┬──────────┬────────────┤
│    Action       │  Admin  │ Seller  │   User   │   Guest    │
├─────────────────┼─────────┼─────────┼──────────┼────────────┤
│ View LP's       │    ✓    │    ✓    │    ✓     │     ✗      │
│ Create LP's     │    ✓    │    ✓    │    ✗     │     ✗      │
│ Edit LP's       │    ✓    │    ✓    │    ✗     │     ✗      │
│ Delete LP's     │    ✓    │    ✗    │    ✗     │     ✗      │
│ Admin Dashboard │    ✓    │    ✗    │    ✗     │     ✗      │
│ Seller Dashboard│    ✗    │    ✓    │    ✗     │     ✗      │
│ User Dashboard  │    ✗    │    ✗    │    ✓     │     ✗      │
│ Main Dashboard  │    ✓    │    ✓    │    ✓     │     ✗      │
└─────────────────┴─────────┴─────────┴──────────┴────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    ROUTE STRUCTURE                            │
└─────────────────────────────────────────────────────────────┘

PUBLIC
  GET  /                              → welcome page
  GET  /login                         → login form
  POST /login                         → process login
  GET  /register                      → registration form
  POST /register                      → process registration

AUTHENTICATED (middleware: auth)
  POST /logout                        → logout user
  GET  /dashboard                     → main dashboard
  GET  /lps                           → view all LP's
  GET  /lps/{id}                      → view single LP

ADMIN ONLY (middleware: auth, role:admin)
  GET  /dashboard/admin               → admin panel
  DELETE /lps/{id}                    → delete LP

SELLER ONLY (middleware: auth, role:seller)
  GET  /dashboard/seller              → seller panel

USER ONLY (middleware: auth, role:user)
  GET  /dashboard/user                → user panel

ADMIN & SELLER (middleware: auth, role:admin,seller)
  GET  /lps/create                    → create LP form
  POST /lps                           → store LP
  GET  /lps/{id}/edit                 → edit LP form
  PUT  /lps/{id}                      → update LP

┌─────────────────────────────────────────────────────────────┐
│                    MIDDLEWARE FLOW                            │
└─────────────────────────────────────────────────────────────┘

Request → CheckRole Middleware
          │
          ├─ Not authenticated? → Redirect to /login
          │
          ├─ Wrong role? → 403 Forbidden
          │
          └─ Correct role? → Allow access to route
                             │
                             ▼
                        Controller → View

┌─────────────────────────────────────────────────────────────┐
│                   USER MODEL METHODS                          │
└─────────────────────────────────────────────────────────────┘

$user->isAdmin()              → Returns true if user is admin
$user->isSeller()             → Returns true if user is seller
$user->isUser()               → Returns true if user is regular user
$user->hasRole('admin')       → Check for specific role
$user->hasRole(['admin','seller']) → Check for multiple roles

┌─────────────────────────────────────────────────────────────┐
│                    VIEW USAGE EXAMPLES                        │
└─────────────────────────────────────────────────────────────┘

@auth
    <!-- User is logged in -->
    <p>Welcome, {{ auth()->user()->name }}</p>
@endauth

@if(auth()->user()->isAdmin())
    <!-- Show admin controls -->
    <a href="{{ route('dashboard.admin') }}">Admin Panel</a>
@endif

@if(auth()->user()->hasRole(['admin', 'seller']))
    <!-- Show seller/admin controls -->
    <a href="{{ route('lps.create') }}">Add LP</a>
@endif
