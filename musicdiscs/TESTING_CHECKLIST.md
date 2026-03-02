# Testing Checklist - Account System

Use this checklist to verify all features are working correctly.

## ✅ Authentication Tests

### Registration
- [ ] Visit `/register`
- [ ] Register as Admin with email: `test-admin@example.com`
- [ ] Register as Seller with email: `test-seller@example.com`
- [ ] Register as User with email: `test-user@example.com`
- [ ] Verify password confirmation validation
- [ ] Verify email uniqueness validation
- [ ] Verify automatic login after registration
- [ ] Verify redirect to dashboard after registration

### Login
- [ ] Visit `/login`
- [ ] Login with admin@example.com / password
- [ ] Verify "Remember Me" checkbox works
- [ ] Test invalid credentials (should show error)
- [ ] Test wrong email (should show error)
- [ ] Test wrong password (should show error)
- [ ] Verify redirect to dashboard on success

### Logout
- [ ] Click logout from any dashboard
- [ ] Verify session is destroyed
- [ ] Verify redirect to login page
- [ ] Try accessing protected route after logout (should redirect to login)

## ✅ Authorization Tests

### Admin Role Tests
- [ ] Login as admin@example.com
- [ ] Access `/dashboard` - should work ✓
- [ ] Access `/dashboard/admin` - should work ✓
- [ ] Access `/lps` - should work ✓
- [ ] Access `/lps/create` - should work ✓
- [ ] Create a new LP - should work ✓
- [ ] Edit an LP - should work ✓
- [ ] Delete an LP - should work ✓
- [ ] Verify admin panel shows in navigation
- [ ] Verify "Add LP" link shows in navigation

### Seller Role Tests
- [ ] Login as seller@example.com
- [ ] Access `/dashboard` - should work ✓
- [ ] Access `/dashboard/seller` - should work ✓
- [ ] Access `/dashboard/admin` - should show 403 ✗
- [ ] Access `/lps` - should work ✓
- [ ] Access `/lps/create` - should work ✓
- [ ] Create a new LP - should work ✓
- [ ] Edit an LP - should work ✓
- [ ] Try to delete an LP - should show 403 ✗
- [ ] Verify seller dashboard shows in navigation
- [ ] Verify "Add LP" link shows in navigation
- [ ] Verify admin panel does NOT show

### User Role Tests
- [ ] Login as user@example.com
- [ ] Access `/dashboard` - should work ✓
- [ ] Access `/dashboard/user` - should work ✓
- [ ] Access `/dashboard/admin` - should show 403 ✗
- [ ] Access `/dashboard/seller` - should show 403 ✗
- [ ] Access `/lps` - should work ✓
- [ ] Access `/lps/create` - should show 403 ✗
- [ ] Try to create an LP - should show 403 ✗
- [ ] Try to edit an LP - should show 403 ✗
- [ ] Try to delete an LP - should show 403 ✗
- [ ] Verify "Add LP" link does NOT show
- [ ] Verify admin/seller panels do NOT show

## ✅ UI/UX Tests

### Navigation
- [ ] Navigation shows "Login" and "Register" when logged out
- [ ] Navigation shows user name and role when logged in
- [ ] Navigation shows role-appropriate links
- [ ] Dropdown menu works for user actions
- [ ] Logout button appears in dropdown

### Dashboard
- [ ] Main dashboard shows user info correctly
- [ ] Dashboard shows correct role badge
- [ ] Quick action cards appear based on role
- [ ] Admin sees admin-specific alerts
- [ ] Seller sees seller-specific alerts
- [ ] User sees user-specific alerts

### Flash Messages
- [ ] Success message shows after login
- [ ] Success message shows after registration
- [ ] Success message shows after logout
- [ ] Error message shows for wrong credentials
- [ ] Error message shows for unauthorized access
- [ ] Messages are dismissible

### Forms
- [ ] Login form has email and password fields
- [ ] Login form has remember me checkbox
- [ ] Registration form has all required fields
- [ ] Registration form has role dropdown
- [ ] Form validation errors display correctly
- [ ] Old input is preserved on validation errors

## ✅ Security Tests

### Password Security
- [ ] Passwords are hashed (not visible in database)
- [ ] Login works with correct password
- [ ] Login fails with incorrect password
- [ ] Password confirmation works during registration

### CSRF Protection
- [ ] All forms have @csrf token
- [ ] Forms fail without valid CSRF token
- [ ] Token regenerates on login

### Session Security
- [ ] Session ID changes after login
- [ ] Session is destroyed on logout
- [ ] Old sessions don't work after logout
- [ ] Protected routes require authentication

### Authorization
- [ ] Middleware blocks unauthorized users
- [ ] 403 error shows for wrong role
- [ ] Cannot access routes via direct URL if unauthorized
- [ ] API/AJAX requests also check authorization

## ✅ Database Tests

### User Table
- [ ] Users have `id`, `name`, `email`, `password`, `role` columns
- [ ] Role defaults to 'user'
- [ ] Email is unique
- [ ] Timestamps are populated
- [ ] Password is hashed with bcrypt

### Seeded Data
- [ ] Admin user exists in database
- [ ] Seller user exists in database
- [ ] User user exists in database
- [ ] All seeded users have correct roles

## ✅ Route Tests

### Public Routes (no auth required)
- [ ] GET / - accessible
- [ ] GET /login - accessible
- [ ] POST /login - accessible
- [ ] GET /register - accessible
- [ ] POST /register - accessible

### Protected Routes (auth required)
- [ ] All dashboard routes require login
- [ ] All LP routes require login
- [ ] Redirect to /login when not authenticated

### Role-Specific Routes
- [ ] Admin routes require admin role
- [ ] Seller routes require seller role
- [ ] User routes require user role
- [ ] Admin+Seller routes work for both

## 🐛 Edge Cases

- [ ] Registering with existing email shows error
- [ ] Very long names/emails are handled
- [ ] Special characters in password work
- [ ] Multiple login attempts don't cause issues
- [ ] Concurrent sessions are handled
- [ ] Browser back button doesn't expose sensitive data
- [ ] Form resubmission is prevented
- [ ] XSS attempts are sanitized

## 📱 Responsive Design

- [ ] Login page looks good on mobile
- [ ] Registration page looks good on mobile
- [ ] Dashboard looks good on mobile
- [ ] Navigation menu works on mobile
- [ ] Forms are usable on mobile
- [ ] Buttons are tappable on mobile

## 🎯 Performance

- [ ] Pages load quickly
- [ ] Database queries are efficient
- [ ] No N+1 query issues
- [ ] Session storage works correctly
- [ ] Cache is utilized where appropriate

---

## Test Results Summary

Date: _______________
Tester: _______________

Total Tests: _____ / _____
Passed: _____
Failed: _____
Not Tested: _____

### Critical Issues Found:
1. 
2. 
3. 

### Minor Issues Found:
1. 
2. 
3. 

### Notes:


---

## Quick Test Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Re-run migrations
php artisan migrate:fresh --seed

# View all routes
php artisan route:list

# Check for errors
php artisan about

# Start development server
php artisan serve
```
