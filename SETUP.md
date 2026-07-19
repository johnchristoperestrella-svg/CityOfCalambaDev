# SETUP INSTRUCTIONS - Calamba PopDev Resource Network

## Quick Start Guide (5 minutes)

### Step 1: Start XAMPP
1. Open XAMPP Control Panel
2. Start Apache (MySQL should also be started)

### Step 2: Create Database
```sql
-- Option A: Using phpMyAdmin (recommended)
1. Go to http://localhost/phpmyadmin
2. Click "New" to create database
3. Database name: calamba_popdev
4. Collation: utf8mb4_unicode_ci
5. Click Create
6. Select the database and go to "Import"
7. Select file: database/migrations/001_create_initial_tables.sql
8. Click Import

-- Option B: Using MySQL Command Line
mysql -u root -p
CREATE DATABASE IF NOT EXISTS calamba_popdev;
USE calamba_popdev;
source "path/to/database/migrations/001_create_initial_tables.sql";
exit;
```

### Step 3: Configure Application
1. Copy .env.example to .env in the root directory
2. Edit .env with your settings:
   ```
   DB_HOST=127.0.0.1
   DB_DATABASE=calamba_popdev
   DB_USERNAME=root
   DB_PASSWORD=
   APP_URL=http://localhost/CityOfCalambaDev
   ```

### Step 4: Access the Application
1. Open your browser
2. Go to: http://localhost/CityOfCalambaDev/public
3. Login with:
   - Email: admin@calamba.gov.ph
   - Password: DefaultPass@123

### Step 5: Create Your Account
After first login, you can:
1. Go to Security & Governance module
2. Create additional user accounts with different roles
3. Assign permissions based on role

---

## DETAILED SETUP GUIDE

### Requirements
- XAMPP with PHP 7.4+ and MySQL 5.7+
- 200MB free disk space
- Modern web browser (Chrome, Firefox, Edge)
- Optional: Firebase account for real-time features

### Installation Steps

#### 1. Extract Project Files
```
c:\xampp\htdocs\CityOfCalambaDev\
```

#### 2. Install Composer Dependencies (Optional)
```bash
cd c:\xampp\htdocs\CityOfCalambaDev
composer install
```
(Note: All required dependencies are already included)

#### 3. Database Setup
**Using phpMyAdmin (Easiest):**
```
1. Go to http://localhost/phpmyadmin
2. Login (default: root/no password)
3. Create new database named "calamba_popdev"
4. Click on the database
5. Go to "Import" tab
6. Choose file: database/migrations/001_create_initial_tables.sql
7. Click "Go" to execute
```

**Using Command Line:**
```bash
mysql -u root -p < database/migrations/001_create_initial_tables.sql
```

#### 4. Environment Configuration
```bash
# Copy the example environment file
copy .env.example .env

# Edit .env with your settings
# Important: DB_PASSWORD should be empty for default XAMPP setup
```

#### 5. Verify Installation
1. Access: http://localhost/CityOfCalambaDev/public
2. You should see the login page
3. If you see "Error 404", check .htaccess in public folder

---

## DEFAULT CREDENTIALS

After database setup, you can login with:

**Admin Account:**
- Email: admin@calamba.gov.ph
- Password: DefaultPass@123
- Role: City Administrator
- Status: Active

**Change Password:**
1. Login with default credentials
2. Create your own admin account with Security & Governance module
3. Delete or deactivate default account

---

## USER ROLES & CAPABILITIES

### 1. City Administrator
- Full access to all modules
- Manage users and permissions
- View audit logs
- Access security settings
- Create/edit/delete all records

### 2. POPDEV Manager
- Manage data (barangay, household, individual records)
- Create and manage health metrics
- Upload documents
- View all analytics
- Cannot access security module

### 3. Barangay Data Encoder
- Add/edit household and individual records
- Update health metrics for assigned barangay
- Upload documents
- View reports for assigned barangay
- Cannot manage other barangays

### 4. Analyst
- Read-only access to all data
- View all dashboards and reports
- Run ML analytics
- View decision support recommendations
- Cannot edit or delete records

### 5. Viewer
- Read-only access to public dashboards
- View summary statistics
- Cannot access detailed records
- Cannot edit any data

---

## MODULES OVERVIEW

### Dashboard
- Summary of all key metrics
- Quick access to main functions
- Recent activity log

### Data Management
- Manage 5 barangays with 211,000 population
- Track household records
- Individual record management
- Data quality monitoring

### Barangay Records
- Health metrics by barangay
- Immunization coverage statistics
- Mortality rates (maternal, infant, under-5)
- Malnutrition data (wasting, stunting, underweight)
- Water and sanitation access

### Knowledge Management
- Document repository with 1,247+ documents
- Best practices from 54 barangays
- 234+ active contributors
- Monthly views: 8,956+

### ML Analytics
- Decision Trees for socioeconomic status prediction
- Random Forest ensemble methods
- K-Means clustering (5 clusters)
- Regression forecasting
- Feature importance analysis

### Decision Support
- Executive dashboards
- 847+ generated reports
- 18 active policy simulations
- Performance tracking
- Intervention priorities

### Security & Governance (Admin Only)
- User management (82 active users)
- Role-based access control
- Audit logging
- System security status
- Backup management
- Compliance monitoring (93% score)

---

## FIRST TIME SETUP CHECKLIST

- [ ] XAMPP running (Apache + MySQL)
- [ ] Database created (calamba_popdev)
- [ ] Migration tables imported
- [ ] .env file configured
- [ ] Application accessible at localhost/CityOfCalambaDev/public
- [ ] Login successful with admin account
- [ ] Create admin user account
- [ ] Assign user roles
- [ ] Upload sample documents
- [ ] Verify all modules accessible

---

## COMMON ISSUES & SOLUTIONS

### Issue: "Database connection failed"
**Solution:**
1. Check MySQL is running in XAMPP
2. Verify database name in .env (calamba_popdev)
3. Verify username (root) and password are correct
4. Restart MySQL service

### Issue: "Page not found (404 error)"
**Solution:**
1. Ensure .htaccess exists in public/ folder
2. Enable mod_rewrite in Apache:
   - Edit: xampp\apache\conf\httpd.conf
   - Find: LoadModule rewrite_module
   - Remove # from beginning of line
   - Restart Apache
3. Check URL: http://localhost/CityOfCalambaDev/public

### Issue: "Login fails with correct credentials"
**Solution:**
1. Check session directory has write permissions
2. Verify browser allows cookies
3. Check .env DATABASE configuration
4. Try clearing browser cache

### Issue: "Graphs and charts not displaying"
**Solution:**
1. Check Chart.js library is loaded (browser console)
2. Verify JSON API responses (F12 > Network)
3. Check browser console for JavaScript errors

### Issue: "File upload fails"
**Solution:**
1. Create documents folder: public/documents/
2. Set folder permissions: 755
3. Check PHP max_upload_size in .env
4. Verify disk space available

---

## SECURITY RECOMMENDATIONS

1. **Change Default Passwords**
   - Login with admin account
   - Create new admin user
   - Delete default credentials

2. **Configure Firewall**
   - Restrict access to localhost for development
   - Use HTTPS in production
   - Enable firewall rules

3. **Database Security**
   - Change MySQL root password
   - Create database user with limited privileges
   - Enable MySQL user authentication

4. **File Permissions**
   - Set proper folder permissions (755)
   - Protect .env file (644)
   - Restrict public document access

5. **Regular Backups**
   - Export database weekly
   - Backup uploaded files
   - Store in secure location

---

## MAINTENANCE TASKS

### Daily
- Check audit logs for suspicious activity
- Monitor active users
- Review failed login attempts

### Weekly
- Backup database
- Review new documents uploaded
- Check data quality metrics

### Monthly
- Analyze user activity
- Update health metrics
- Review ML model accuracy
- Plan interventions based on data

### Quarterly
- Full system review
- Security audit
- Performance optimization
- Update documentation

---

## FIREBASE INTEGRATION (OPTIONAL)

To enable real-time features:

1. Create Firebase Project
   - Go to https://console.firebase.google.com
   - Create new project
   - Enable Realtime Database

2. Get Credentials
   - Project Settings > Service Account
   - Generate new private key (JSON)
   - Copy key details to .env

3. Update .env
   ```
   FIREBASE_API_KEY=your_api_key
   FIREBASE_AUTH_DOMAIN=your_project.firebaseapp.com
   FIREBASE_DATABASE_URL=https://your_project.firebaseio.com
   FIREBASE_PROJECT_ID=your_project_id
   FIREBASE_STORAGE_BUCKET=your_project.appspot.com
   FIREBASE_MESSAGING_SENDER_ID=123456789
   FIREBASE_APP_ID=1:123456789:web:abc123def456
   FIREBASE_PRIVATE_KEY=-----BEGIN PRIVATE KEY-----...
   FIREBASE_CLIENT_EMAIL=firebase-adminsdk@your-project.iam.gserviceaccount.com
   ```

4. Uncomment Firebase code in controllers

---

## PERFORMANCE TUNING

### Database Optimization
```sql
-- Analyze tables
ANALYZE TABLE barangays;
ANALYZE TABLE households;
ANALYZE TABLE individuals;
ANALYZE TABLE health_metrics;
```

### PHP Configuration (php.ini)
```ini
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 50M
post_max_size = 50M
```

### Apache Configuration
- Enable gzip compression
- Set proper cache headers
- Enable mod_deflate

---

## DEVELOPMENT TIPS

### Adding New Module
1. Create controller in app/Controllers/
2. Create model in app/Models/
3. Add routes in public/index.php
4. Create views in resources/views/
5. Add navigation item in layout

### Database Migration
1. Create SQL file in database/migrations/
2. Document schema changes
3. Test on development first
4. Keep backup of original schema

### Testing Changes
1. Use browser DevTools (F12)
2. Check Console tab for errors
3. Use Network tab to inspect API calls
4. Test with different user roles

---

## SUPPORT & DOCUMENTATION

- **Documentation**: README.md in root folder
- **Database Schema**: database/migrations/
- **API Reference**: Check public/index.php routes
- **Code Examples**: Check controller methods
- **Contact**: dev@calamba.gov.ph

---

**Installation Complete!**

Your Calamba PopDev Resource Network is ready to use.
For assistance, refer to the README.md file or contact the development team.

Last Updated: April 21, 2026
