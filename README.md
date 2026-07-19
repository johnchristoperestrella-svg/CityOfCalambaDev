# 🎯 Calamba PopDev Resource Network - Complete Setup Guide

Welcome! This is a comprehensive system for managing population development resources in the City of Calamba.

---

## 📋 What is This System?

The **Calamba PopDev Resource Network** is a web application that helps manage:
- 📊 **Population Data** - Barangay, household, and individual records
- 🏥 **Health Metrics** - Vaccination, mortality, and nutrition data
- 📚 **Knowledge Base** - Documents and best practices
- 🤖 **Smart Analytics** - Predictions and data analysis
- 🎯 **Decision Support** - Help for policymaking
- 🔐 **User Management** - Security and access control

---

## ✅ Prerequisites (What You Need)

Before starting, make sure you have:

1. **XAMPP Installed** - Download from https://www.apachefriends.org/
   - Includes PHP, MySQL, Apache, and other tools
   
2. **MySQL Running** - Start XAMPP Control Panel and click "Start" for MySQL

3. **Administrator Access** - You need permission to install software

4. **Basic Text Editor** - Notepad++ or VS Code (optional but helpful)

---

## 🚀 QUICKEST WAY TO RUN (START HERE!)

### Option A: Using PHP Built-in Server (EASIEST)

1. **Open Command Prompt or PowerShell** on your computer

2. **Run this command:**
   ```bash
   cd c:\xampp\htdocs\CityOfCalambaDev\public
   php -S localhost:8000
   ```

3. **Open your browser** and go to:
   ```
   http://localhost:8000
   ```

4. **Login with:**
   - Email: `admin@calamba.gov.ph`
   - Password: `DefaultPass@123`

✅ **That's it! The system is running!**

---

### Option B: Using XAMPP Apache Server

1. **Start XAMPP:**
   - Open XAMPP Control Panel
   - Click "Start" next to Apache
   - Click "Start" next to MySQL

2. **Open your browser** and go to:
   ```
   http://localhost/CityOfCalambaDev/public
   ```

3. **Login with the same credentials above**

---

## 🔧 FULL SETUP INSTRUCTIONS (Detailed)

### Step 1: Ensure MySQL Database Exists

1. **Open XAMPP Control Panel**
2. **Click "Shell" button** at bottom right
3. **Type these commands:**
   ```bash
   mysql -u root -p
   ```
   (Press Enter, no password needed if you haven't set one)

4. **Then type:**
   ```sql
   CREATE DATABASE IF NOT EXISTS calamba_popdev;
   USE calamba_popdev;
   SOURCE database/migrations/001_create_initial_tables.sql;
   EXIT;
   ```

5. **If you see "Query OK"** - Database is ready! ✅

### Step 2: Configure Environment File (Optional)

1. **Navigate to folder:**
   ```
   c:\xampp\htdocs\CityOfCalambaDev
   ```

2. **Create a file named `.env`** (if it doesn't exist)

3. **Add this content:**
   ```
   DB_HOST=127.0.0.1
   DB_DATABASE=calamba_popdev
   DB_USERNAME=root
   DB_PASSWORD=
   DB_PORT=3306
   APP_NAME=Calamba PopDev Resource Network
   APP_URL=http://localhost:8000
   ```

4. **Save the file**

### Step 3: Start the Server

**Choose ONE method:**

**Method A - PHP Server (Recommended):**
```bash
cd c:\xampp\htdocs\CityOfCalambaDev\public
php -S localhost:8000
```

**Method B - Apache Server:**
- Open XAMPP Control Panel
- Start Apache
- Go to `http://localhost/CityOfCalambaDev/public`

### Step 4: Access the Application

1. **Open your browser** (Chrome, Firefox, Edge, etc.)

2. **Go to:** `http://localhost:8000` (or your configured URL)

3. **You should see the login page** with beautiful design ✨

4. **Login with:**
   - Email: `admin@calamba.gov.ph`
   - Password: `DefaultPass@123`

5. **After login, change your password!** 🔐

---

## 👤 User Roles & What Each Can Do

When you create users, assign them one of these roles:

| Role | Can Do | Cannot Do |
|------|--------|-----------|
| **🔑 Admin** | Everything | Nothing (full access) |
| **📊 POPDEV Manager** | Manage data, view analytics | Manage users, change security |
| **📝 Data Encoder** | Add/edit barangay data only | View other modules |
| **👁️ Analyst** | View analytics and reports | Add/edit/delete data |
| **👀 Viewer** | View public dashboards only | Anything else |

---

## 🎯 How to Use the System

### 1. **Dashboard** (After Login)
- See overview of all data
- View key statistics
- Access quick actions

### 2. **📋 Data Management**
- View barangay records
- Add new households
- Track individuals
- Manage data quality

### 3. **🏘️ Barangay Records**
- Track health metrics
- View vaccination coverage
- Monitor mortality rates
- Track malnutrition data
- View water and sanitation access

### 4. **📚 Knowledge Management**
- Upload documents
- Organize by category
- Share best practices
- View document statistics

### 5. **🤖 ML Analytics**
- See predictions for socioeconomic status
- View population forecasts
- See community clusters
- Analyze important features
- Get risk assessments

### 6. **🎯 Decision Support**
- Executive dashboards
- Policy simulations
- Performance tracking
- Intervention priorities

### 7. **🔐 Security & Governance** (Admin Only)
- Create and manage users
- Change user roles
- View audit logs
- Manage security settings

---

## 📁 Folder Structure (What Goes Where)

```
CityOfCalambaDev/
├── public/                  # FRONT-END (What users see)
│   ├── index.php           # Main page
│   ├── css/                # Styles and design files
│   ├── js/                 # JavaScript for interactions
│   └── documents/          # Uploaded files
│
├── app/                     # BACK-END (System logic)
│   ├── Controllers/         # Handle page logic
│   ├── Models/              # Database operations
│   └── ML_Models/           # Smart analytics
│
├── resources/views/         # PAGE TEMPLATES
│   ├── auth/               # Login page
│   ├── dashboard/          # Dashboard pages
│   ├── data-import/        # Data upload pages
│   └── ...other modules
│
├── config/                 # SETTINGS
│   ├── database.php        # How to connect to MySQL
│   └── helpers.php         # Helper functions
│
├── database/               # DATABASE FILES
│   └── migrations/         # Database structure setup
│
└── .env                    # SECRET SETTINGS (Don't share!)
```

---

## 🔧 Common Tasks & How to Do Them

### ➕ Add a New User
1. Go to **🔐 Security & Governance** (Admin only)
2. Click **"Add New User"**
3. Fill in:
   - Full Name
   - Email Address
   - Temporary Password
   - Role (choose from the list)
4. Click **"Create User"**
5. New user gets an email with login info

### 📊 Add Barangay Data
1. Go to **🏘️ Barangay Records**
2. Click **"Add New Barangay"**
3. Enter:
   - Barangay Name
   - Population
   - Area
   - Chairman Name
   - Contact Info
4. Click **"Save"**

### 📤 Import Data from Excel
1. Go to **📋 Data Management** → **"Excel Import"**
2. Click **"Choose File"**
3. Select your Excel file
4. Map columns (which column = which field)
5. Click **"Import"**
6. System shows results: ✅ Success or ❌ Errors

### 🔍 View Analytics
1. Go to **🤖 ML Analytics**
2. Choose what you want to see:
   - Population forecasts
   - Risk predictions
   - Community clusters
   - Trend analysis
3. Results show as charts and graphs

### 📄 Upload Document
1. Go to **📚 Knowledge Management**
2. Click **"Upload Document"**
3. Choose file and category
4. Add title and description
5. Click **"Upload"**

---

## 🛠️ Troubleshooting (Common Problems & Solutions)

### ❌ "Can't connect to database"
**Problem**: Server won't start because database isn't working

**Solution**:
1. Open XAMPP Control Panel
2. Make sure MySQL is running (green status)
3. If not, click "Start" for MySQL
4. Wait 10 seconds and try again

### ❌ "Page not found (404 error)"
**Problem**: Getting error when accessing the site

**Solution**:
1. Make sure you're going to the right URL:
   - PHP Server: `http://localhost:8000`
   - Apache Server: `http://localhost/CityOfCalambaDev/public`
2. Restart the server
3. Clear browser cache (Ctrl+Shift+Delete)

### ❌ "Login not working"
**Problem**: Can't log in with provided credentials

**Solution**:
1. Check you're typing the email correctly: `admin@calamba.gov.ph`
2. Check password is exactly: `DefaultPass@123`
3. If still not working, contact administrator

### ❌ "Slow performance"
**Problem**: Website is loading slowly

**Solution**:
1. Close other programs using internet
2. Refresh the page (F5)
3. If using Apache, add more RAM to PHP settings
4. Reduce number of open browser tabs

### ❌ "Uploaded file won't display"
**Problem**: Can't see document after uploading

**Solution**:
1. Check file format is supported (PDF, DOC, XLS, etc.)
2. Check file size is under 10MB
3. Try uploading again
4. Check browser downloads folder - file might be there

### ❌ "Changes aren't saving"
**Problem**: When you save, changes disappear

**Solution**:
1. Check that database is connected (MySQL running)
2. Make sure you click "Save" button completely
3. Wait for confirmation message
4. Refresh page to verify changes saved

---

## 📊 System Features Explained

### 🤖 Machine Learning Analytics

The system can predict and analyze data automatically:

**Decision Tree**
- Predicts if household is vulnerable
- Based on income, family size, education, health

**Random Forest**
- More accurate predictions
- Combines multiple decision trees
- Good for household classification

**K-Means Clustering**
- Groups similar households together
- Creates 5 groups for targeted help
- Based on family characteristics

**Regression Analysis**
- Predicts future population
- Forecasts trends
- Identifies risks

### 📈 Dashboards

Different dashboards for different needs:

- **Admin Dashboard**: All data, all statistics
- **Manager Dashboard**: Data for assigned barangays
- **Analyst Dashboard**: Read-only analytics
- **Executive Dashboard**: High-level overview

---

## 🔐 Security & Safety

### Password Security
- Always use strong passwords (upper, lower, numbers, symbols)
- Don't share your password
- Change password every 90 days
- Never save password in browser on shared computers

### Data Protection
- System logs all changes (who did what, when)
- Users can only see data they have permission to
- Email and passwords are encrypted
- Regular backups are made

### Best Practices
1. ✅ Always logout when done
2. ✅ Lock computer before leaving desk
3. ✅ Report suspicious activity immediately
4. ✅ Don't use public WiFi without VPN
5. ✅ Keep PHP and MySQL updated

---

## 📞 Support & Getting Help

### For Technical Issues:
1. Check **Troubleshooting** section above
2. Look at error message carefully (it tells you what's wrong)
3. Try restarting the server
4. Check that XAMPP services are running

### For Usage Questions:
1. Check the **How to Use the System** section
2. Look at **Common Tasks** section
3. Ask your administrator for help

### Contact Information:
- **Email**: dev@calamba.gov.ph
- **Office**: City Development Office
- **Hours**: Monday - Friday, 8:00 AM - 5:00 PM

---

## 📈 Important Information

### Default Login
```
Email: admin@calamba.gov.ph
Password: DefaultPass@123
```
⚠️ **Change this password immediately after first login!**

### Database Information
```
Host: 127.0.0.1 (localhost)
Database Name: calamba_popdev
Username: root
Password: (empty by default)
Port: 3306
```

### Technology Stack
- **Language**: PHP 8.0+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (or PHP built-in server)
- **Charts**: Chart.js

---

## 🎓 Learning Resources

### Getting Started
1. Watch login tutorial (in Help menu)
2. Try dashboard with demo data
3. Practice with safe, non-live data first
4. Ask for administrator guidance

### Advanced Features
1. **Data Analysis**: See ML Analytics module
2. **Reports**: Go to Decision Support for reports
3. **Integration**: API documentation available
4. **Customization**: Contact developers for changes

---

## 🚨 If Server Stops or Won't Start

### PHP Server stopped
1. Open Command Prompt/PowerShell
2. Run: `cd c:\xampp\htdocs\CityOfCalambaDev\public`
3. Run: `php -S localhost:8000`
4. If error, check that MySQL is running

### Apache Server issues
1. Open XAMPP Control Panel
2. Click "Stop" for Apache
3. Wait 5 seconds
4. Click "Start" for Apache
5. Refresh your browser

### MySQL not running
1. Open XAMPP Control Panel
2. Click "Start" for MySQL
3. Wait 10 seconds
4. Try connecting to application again

---

## ✨ New Beautiful UI Features

Your system now has:
- 🎨 **Modern Design** - Professional appearance
- 📱 **Responsive** - Works on phones, tablets, desktop
- ⚡ **Fast** - Smooth animations
- 🎯 **Easy Navigation** - Clear menu structure
- 🌈 **Beautiful Colors** - Pleasing to the eye
- 📊 **Great Charts** - Easy to understand data
- ♿ **Accessible** - Works for everyone

See **UI_DESIGN_GUIDE.md** for design documentation.

---

## 📚 Additional Documentation

For more detailed information, see these files:

- **`UI_DESIGN_GUIDE.md`** - About the beautiful interface
- **`QUICK_REFERENCE.md`** - Quick command reference
- **`DEPLOYMENT_COMPLETE.md`** - Deployment information
- **`SETUP.md`** - Detailed setup information

---

## 🎯 Next Steps After Installation

### Day 1
1. ✅ Log in with admin account
2. ✅ Change admin password
3. ✅ Explore the dashboard
4. ✅ Review all modules

### Week 1
1. ✅ Create user accounts for team
2. ✅ Upload sample barangay data
3. ✅ Add health metrics
4. ✅ Test data imports

### Ongoing
1. ✅ Regular data backups
2. ✅ Monitor user activity
3. ✅ Update user information
4. ✅ Review analytics regularly

---

## 💡 Pro Tips

1. **Backup Your Data** - Regularly save database backups
2. **Use Strong Passwords** - Make them hard to guess
3. **Update Regularly** - Check for system updates
4. **Test First** - Try new features with test data
5. **Document Changes** - Keep notes of modifications
6. **Train Your Team** - Show them how to use each feature
7. **Monitor Performance** - Check system regularly
8. **Report Issues** - Tell administrator about problems

---

## ⚠️ Important Reminders

- 🔐 **Never share passwords** - Each user gets their own
- 🔄 **Backup data regularly** - You need to backup yourself
- 📝 **Log activities** - System logs are for auditing
- 👥 **Manage users carefully** - Remove old users, update roles
- 🔒 **Keep server secure** - Don't expose to internet without security
- ⏰ **Plan maintenance** - Do backups when system has low use
- 📞 **Have contact info** - Know who to call if problems occur
- 📚 **Read documentation** - It's here for a reason!

---

## 📋 System Checklist

Before going live, verify:

- ✅ MySQL is running
- ✅ All users can login
- ✅ Can upload files
- ✅ Analytics display correctly
- ✅ Database connection is working
- ✅ Backups are working
- ✅ All users know their passwords
- ✅ Admin has secure password
- ✅ System is not exposed to public internet unsecured
- ✅ Training has been completed

---

## 🎁 What's Included

Your system includes:

✅ Complete data management system
✅ Health metrics tracking
✅ Machine learning analytics
✅ Knowledge management
✅ Decision support tools
✅ Security system
✅ User management
✅ Beautiful modern interface
✅ Complete documentation
✅ Example data

---

## 📞 Quick Reference

| Need | Do This |
|------|---------|
| Start server | `php -S localhost:8000` in public folder |
| Access system | Go to `http://localhost:8000` |
| Default login | admin@calamba.gov.ph / DefaultPass@123 |
| Create user | Go to Security & Governance (Admin) |
| Import data | Go to Data Management → Excel Import |
| View analytics | Go to ML Analytics |
| Check logs | Go to Security & Governance → Audit Logs |
| Backup database | Use XAMPP phpMyAdmin or command line |
| Stop server | Press Ctrl+C in command prompt |

---

## 🏁 Ready to Go!

**Your system is now ready to use!**

### Quick Start Checklist:
1. ✅ Run the server (PHP server or Apache)
2. ✅ Go to http://localhost:8000
3. ✅ Login with admin account
4. ✅ Change your password
5. ✅ Start using the system!

### If You Get Stuck:
1. Check the **Troubleshooting** section
2. Read the relevant documentation file
3. Check the Help menu in the application
4. Contact your administrator

---

## 📅 Version & Updates

**Current Version**: 1.0
**Last Updated**: April 27, 2026
**Status**: ✅ Production Ready

---

## 📄 License & Copyright

© 2026 City of Calamba - All Rights Reserved

This system is created for the exclusive use of the City of Calamba
Population Development Office. Unauthorized use, copying, or distribution
is prohibited.

---

## 🙏 Thank You

Thank you for using the Calamba PopDev Resource Network!

For questions or feedback, please contact:
- **Development Office**: dev@calamba.gov.ph
- **City Hall**: (631) 410-1234
- **Hours**: Monday - Friday, 8:00 AM - 5:00 PM

**Enjoy using your new system! 🎉**
