# Quick Test Guide - Authentication Pages

## Start the Application

```bash
# Navigate to project directory
cd c:\xampp\htdocs\CityOfCalambaDev

# Start PHP development server
php -S localhost:8000
```

**OR use XAMPP:**
- Start Apache from XAMPP Control Panel
- Navigate to: `http://localhost/CityOfCalambaDev/public/`

---

## Test 1: Login Page (Existing Users)

### Location
```
http://localhost:8000/CityOfCalambaDev/public/
```

### What to See
- Blue gradient background
- White card with "📊 PopDev" title
- "Sign In to Your Account" subtitle
- Email input field
- Password input field
- "Sign In" button
- Link: "Don't have an account? Create one"

### Test Cases

#### Test 1a: Successful Login
1. Enter a valid email (must exist in database)
2. Enter correct password
3. Click "Sign In"
4. Should see: "Sign in successful! Redirecting..."
5. Should redirect to: `/dashboard`

#### Test 1b: Invalid Email
1. Enter invalid email format (e.g., "notanemail")
2. Click "Sign In"
3. Should see error alert

#### Test 1c: Wrong Password
1. Enter valid email that exists
2. Enter incorrect password
3. Click "Sign In"
4. Should see: "Invalid credentials" error

#### Test 1d: Non-existent Email
1. Enter email that doesn't exist in database
2. Enter any password
3. Click "Sign In"
4. Should see: "Invalid credentials" error

#### Test 1e: Loading State
1. Click "Sign In" button
2. Button text should change to: "Signing in..."
3. Button should be disabled (greyed out)

#### Test 1f: Navigation to Register
1. Click "Create one" link
2. Should navigate to: `/register`
3. Should see registration form

---

## Test 2: Register Page (New Users)

### Location
```
http://localhost:8000/CityOfCalambaDev/public/register
```

### What to See
- Blue gradient background (same as login)
- White card with "📊 PopDev" title
- "Create Your Account" subtitle
- Full Name input field
- Email Address input field
- Password input field with requirements
- Confirm Password input field
- "Create Account" button
- Link: "Already have an account? Sign in"

### Password Requirements Display
```
• Minimum 8 characters
• Use letters, numbers, and symbols
```

### Test Cases

#### Test 2a: Successful Registration
1. Enter full name (e.g., "John Doe")
2. Enter new email (not in database)
3. Enter password (min 8 chars, e.g., "Password123!")
4. Enter same password in confirm field
5. Click "Create Account"
6. Should see: "Account created successfully! Redirecting to login..."
7. Should redirect to: `/` (login page)

#### Test 2b: Duplicate Email
1. Enter name (e.g., "Jane Smith")
2. Enter email that already exists in database
3. Enter valid password twice
4. Click "Create Account"
5. Should see: "Email already registered" error

#### Test 2c: Password Too Short
1. Fill all fields
2. Enter password with less than 8 characters (e.g., "Pass123")
3. Click "Create Account"
4. Should see client-side error: "Password must be at least 8 characters"
5. OR if not caught client-side, server response: same error

#### Test 2d: Passwords Don't Match
1. Enter name
2. Enter email
3. Enter password (e.g., "Password123!")
4. Enter different confirm password (e.g., "Different456!")
5. Click "Create Account"
6. Should see: "Passwords do not match" error

#### Test 2e: Empty Fields
1. Leave name field empty
2. Fill other fields
3. Click "Create Account"
4. Should see: "All fields are required" error
5. Test with each field individually empty

#### Test 2f: Invalid Email Format
1. Enter name
2. Enter invalid email (e.g., "notanemail")
3. Enter passwords
4. Click "Create Account"
5. Should see: error about invalid email

#### Test 2g: Loading State
1. Fill form with valid data
2. Click "Create Account" button
3. Button text should change to: "Creating Account..."
4. Button should be disabled

#### Test 2h: Navigation Back to Login
1. Click "Sign in" link
2. Should navigate to: `/` (login page)
3. Should see login form

---

## Test 3: Cross-Page Navigation

### Test 3a: Login → Register
1. Start on login page (`/`)
2. Click "Create one" link
3. Should navigate to register page (`/register`)
4. Should see registration form

### Test 3b: Register → Login
1. Start on register page (`/register`)
2. Click "Sign in" link
3. Should navigate to login page (`/`)
4. Should see login form

---

## Test 4: Complete User Journey (New User)

### Scenario: New user signs up and then logs in

**Step 1: Register**
1. Navigate to `/register`
2. Fill form:
   - Name: "Test User"
   - Email: "testuser@example.com"
   - Password: "SecurePass123!"
   - Confirm: "SecurePass123!"
3. Click "Create Account"
4. Verify: Success message and redirect to login

**Step 2: Login with New Account**
1. On login page now
2. Enter email: "testuser@example.com"
3. Enter password: "SecurePass123!"
4. Click "Sign In"
5. Verify: Success message and redirect to dashboard

**Expected Result:** ✅ User should see dashboard with data

---

## Test 5: Responsive Design

### Desktop (1024px+)
- [ ] Form centered on screen
- [ ] All elements properly aligned
- [ ] Buttons fully visible
- [ ] No horizontal scrolling

### Tablet (768px)
- [ ] Form still centered
- [ ] Input fields readable
- [ ] Button clickable
- [ ] Labels visible

### Mobile (480px and below)
- [ ] Form adapts to smaller width
- [ ] Padding reduced (35px → 25px)
- [ ] Text size adjusted
- [ ] All fields accessible without scrolling
- [ ] Touch-friendly button size (14px padding)

### Test Commands
```bash
# In browser developer tools
# Chrome: Press F12, then Ctrl+Shift+M for mobile view
# Firefox: Press F12, then Ctrl+Shift+M for responsive mode

# Test breakpoints:
- 1920px (desktop)
- 768px (tablet)
- 480px (mobile)
```

---

## Test 6: Browser Compatibility

### Browsers to Test
- [ ] Chrome 90+
- [ ] Firefox 88+
- [ ] Safari 14+
- [ ] Edge 90+

### Each Browser Should Show
- [ ] Gradient background renders correctly
- [ ] Form styling looks good
- [ ] Buttons are clickable
- [ ] Form submission works
- [ ] Alerts display properly
- [ ] Animations play smoothly

---

## Test 7: Error Handling

### Network Error
1. Go to register page
2. Fill form with valid data
3. Disable internet connection (or use DevTools)
4. Click "Create Account"
5. Should see: "An error occurred. Please try again."

### Server Error (500)
1. Fill form with valid data
2. Trigger a server error somehow
3. Should see: Error message from API response
4. Button should be re-enabled
5. Form should remain with data (not cleared)

---

## Database Verification

### Check if Registration Worked
```sql
-- Connect to MySQL
mysql -u root calamba_popdev

-- Check users table
SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC LIMIT 5;

-- Check new user was created
SELECT * FROM users WHERE email = 'testuser@example.com';
```

### Expected Output
```
id: 1, 2, 3...
name: Test User
email: testuser@example.com
role: Analyst
status: active
created_at: 2024-01-15 10:30:45
```

---

## Console Logging (Browser DevTools)

### Open Console
- Chrome: Press F12 → Click "Console" tab
- Firefox: Press F12 → Click "Console" tab

### Look for Messages
```javascript
// Successful login
Login error: (if any errors appear)

// Successful registration
Registration error: (if any errors appear)

// These should NOT appear if working correctly
```

### Network Tab
1. Press F12 → Click "Network" tab
2. Perform login or registration
3. Look for:
   - Request: `POST /api/login` or `POST /api/register`
   - Response: Status 200 (success) or 400/409 (validation error)
   - Response body: Should contain JSON with success/error

---

## Successful Test Results Summary

### ✅ Login Page Works When:
- Displays correctly at `/`
- Form fields accept input
- Valid login creates session and redirects
- Invalid login shows error
- Link to register works
- Mobile responsive

### ✅ Register Page Works When:
- Displays correctly at `/register`
- Form fields accept input
- Valid registration creates user account
- Invalid data shows appropriate errors
- Duplicate email rejected
- Link to login works
- Mobile responsive

### ✅ Complete System Works When:
- New user can register
- New user receives confirmation
- New user can login with credentials
- Dashboard loads after login
- All navigation links work
- Error messages are clear

---

## Troubleshooting Common Issues

### Issue: Page shows 404 Not Found
**Check:**
1. Is server running? (`php -S localhost:8000`)
2. Is URL correct? (`http://localhost:8000/CityOfCalambaDev/public/register`)
3. Are routes defined in `public/index.php`?

### Issue: Form doesn't submit
**Check:**
1. Open browser console (F12)
2. Look for JavaScript errors
3. Check form `action` attribute points to correct URL
4. Verify API endpoint exists

### Issue: Login fails with "Invalid credentials"
**Check:**
1. Is user email in database? Run SQL query above
2. Is password correct?
3. Is user account status "active"?
4. Check database connection works

### Issue: Can't register new account
**Check:**
1. Is users table created? Run: `SHOW TABLES;`
2. Is email unique in table? Check for duplicates
3. Are input validations too strict?
4. Check server error logs for detailed error

### Issue: Mobile view looks broken
**Check:**
1. Browser zoom at 100%?
2. Viewport meta tag present in HTML?
3. CSS media queries applied?
4. Try different browser/device

---

## Performance Testing

### Page Load Time
1. Open DevTools (F12)
2. Go to Performance tab
3. Reload page
4. Check load time: Should be < 1 second

### Form Submission Time
1. Open Network tab
2. Fill form and submit
3. Check request time: Should be < 500ms

### Expected Times
- Login submit: 200-400ms
- Registration submit: 300-500ms
- Redirect: 1000-1500ms (with 1.5s delay)

---

## Sign Off - All Tests Passed ✓

Once you've completed all tests and they pass, mark this checklist:

- [ ] Login page displays correctly
- [ ] Register page displays correctly
- [ ] New users can register successfully
- [ ] Registered users can login successfully
- [ ] Navigation between pages works
- [ ] Error messages display correctly
- [ ] Mobile responsive design works
- [ ] Forms submit without errors
- [ ] Database integration works
- [ ] Session management works
- [ ] Redirects work correctly
- [ ] All browser compatibility tested

**Status: READY FOR PRODUCTION** ✓
