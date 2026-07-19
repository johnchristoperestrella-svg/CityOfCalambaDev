# 🚀 CALAMBA POPDEV RESOURCE NETWORK - QUICK START GUIDE

## ✅ System Status: FULLY OPERATIONAL

The Calamba PopDev Resource Network system has been successfully set up and is ready to use.

---

## 📋 QUICK ACCESS

### Method 1: Development Server (Currently Running)
- **URL**: http://localhost:8000/
- **Status**: ✅ Active
- **Method**: PHP Built-in Server

### Method 2: XAMPP Apache Server
- **URL**: http://localhost/CityOfCalambaDev/public
- **Status**: Available (requires Apache running)

---

## 🔐 LOGIN CREDENTIALS

**Email**: `admin@calamba.gov.ph`
**Password**: `DefaultPass@123`

⚠️ **IMPORTANT**: Change this password after first login!

---

## 📊 SYSTEM INFORMATION

### Database
- **Status**: ✅ Connected
- **Host**: 127.0.0.1
- **Database**: calamba_popdev
- **Tables**: 12 (all created)

### Data Records
- Barangays: 5
- Households: 0 (ready to import)
- Individuals: 0 (ready to import)
- Health Metrics: 5
- Documents: 0

### PHP Environment
- **PHP Version**: 8.0.30
- **All Files**: ✅ Valid syntax
- **Configuration**: ✅ Complete

---

## 🎯 MAIN MODULES

1. **Authentication** - Login & User Management
2. **Dashboard** - Overview and Analytics
3. **Data Management** - Barangay, Household, Individual records
4. **Data Import** - CSV/Excel data import with validation
5. **Barangay Records** - Health metrics and statistics
6. **Knowledge Management** - Document management
7. **ML Analytics** - Machine learning models and predictions
8. **Decision Support** - Analytics dashboards and reports
9. **Security & Governance** - User roles and permissions

---

## 🛠️ HOW TO RESTART SERVER

### Windows
Double-click: `START_SERVER.bat`

Or run in terminal:
```
cd c:\xampp\htdocs\CityOfCalambaDev\public
php -S localhost:8000 -t .
```

### Linux/Mac
```bash
bash START_SERVER.sh
```

---

## 📝 USEFUL COMMANDS

### Check System Status
```
php system_status.php
```

### Test Database Connection
```
php test_connection.php
```

### Check Syntax
```
php check_syntax.php
```

---

## ⚠️ TROUBLESHOOTING

### Issue: "Connection refused"
- **Solution**: Ensure MySQL is running. Start XAMPP or MySQL service.

### Issue: "404 Not Found"
- **Solution**: Make sure you're accessing http://localhost:8000/ correctly

### Issue: "Database does not exist"
- **Solution**: Run `php check_admin.php` to verify database setup

---

## 🔒 SECURITY NOTES

1. Change default admin password immediately
2. Configure Firebase settings in `.env` if using real-time features
3. Regularly backup the database
4. Review audit logs periodically
5. Use proper user roles and permissions

---

## 📚 ADDITIONAL RESOURCES

- **Documentation**: See SETUP.md
- **Database Schema**: database/migrations/001_create_initial_tables.sql
- **Configuration**: .env file
- **Sample Data**: public/uploads/sample_data.csv

---

## ✅ NEXT STEPS

1. ✅ Login to the system
2. ✅ Change your admin password
3. ✅ Create additional user accounts
4. ✅ Configure barangay settings
5. ✅ Import data records
6. ✅ Run ML analytics
7. ✅ Generate reports

---

**System Status**: 🟢 READY TO USE

Generated: $(date)
