# 🗺️ Complete Routes & Page Connections Guide
## Calamba PopDev Resource Network

This document maps all routes, pages, and their connections in the system.

---

## 📊 System Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│              Browser/Client (Frontend)                  │
│         - Navigation Sidebar                            │
│         - Dynamic Page Loading (app.js)                 │
│         - REST API Calls                                │
└────────────────────┬────────────────────────────────────┘
                     │
         HTTP Routes (Router.php)
                     │
┌────────────────────▼────────────────────────────────────┐
│          PHP Application (Backend)                      │
│         - Controllers (Handle Logic)                    │
│         - Models (Database Access)                      │
│         - Views (Page Templates)                        │
└────────────────────┬────────────────────────────────────┘
                     │
         Database Operations
                     │
┌────────────────────▼────────────────────────────────────┐
│         MySQL Database                                  │
│         - Tables & Data Storage                         │
└─────────────────────────────────────────────────────────┘
```

---

## 🔐 Authentication Routes

### Login & Session Management

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Login | `/` | AuthController | login | Display login page |
| Handle Login | `/api/login` (POST) | AuthController | handleLogin | Process login credentials |
| Logout | `/api/logout` (POST) | AuthController | logout | Destroy session, logout user |
| Register | `/api/register` (POST) | AuthController | register | Create new user account |
| User Profile | `/api/user-profile` (GET) | AuthController | getProfile | Get current user info |

**Data Flow:**
```
User enters email/password
      ↓
POST /api/login
      ↓
AuthController validates credentials
      ↓
SessionController stores session
      ↓
Redirect to /dashboard
```

---

## 📊 Dashboard Routes

### Main Dashboard

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Dashboard View | `/dashboard` | DashboardController | index | Show main dashboard |
| Barangays Data | `/api/barangays` (GET) | DataManagementController | getBarangays | Fetch barangay list |
| Households Data | `/api/households` (GET) | DataManagementController | getHouseholds | Fetch household data |
| Individuals Data | `/api/individuals` (GET) | DataManagementController | getIndividuals | Fetch individual records |
| Audit Logs | `/api/audit-logs` (GET) | SecurityGovernanceController | getAuditLogs | Recent system activities |

**Dashboard Display Elements:**
1. **Statistics Cards** - Total counts from API data
2. **Charts** - Population, health coverage, education, socioeconomic
3. **Recent Updates** - Activity from audit logs
4. **KPIs** - Data quality and system health

**JavaScript Flow (app.js):**
```
loadDashboard()
  ├─ Render dashboard HTML template
  ├─ Call loadDashboardData()
  │   ├─ fetch /api/barangays
  │   ├─ fetch /api/households
  │   ├─ fetch /api/individuals
  │   └─ fetch /api/audit-logs
  └─ renderDashboardCharts()
      ├─ Population bar chart
      ├─ Health coverage doughnut
      ├─ Education pie chart
      └─ Socioeconomic horizontal bar
```

---

## 📋 Data Management Module

### Records Management

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Data Management | `/data-management` | DataManagementController | index | Show records page |
| Get Barangays | `/api/barangays` (GET) | DataManagementController | getBarangays | List all barangays |
| Get Households | `/api/households` (GET) | DataManagementController | getHouseholds | List households (filtered) |
| Get Individuals | `/api/individuals` (GET) | DataManagementController | getIndividuals | List individuals (filtered) |
| Data Quality | `/api/data-quality` (GET) | DataManagementController | getDataQuality | Quality metrics |
| Create Barangay | `/api/barangay/create` (POST) | DataManagementController | createBarangay | Add new barangay |
| Update Barangay | `/api/barangay/update/{id}` (PUT) | DataManagementController | updateBarangay | Edit barangay |
| Delete Barangay | `/api/barangay/delete/{id}` (DELETE) | DataManagementController | deleteBarangay | Remove barangay |

**Page Features:**
- Table of barangays with population, area, chairman
- Table of households with head, address, members
- Table of individuals with demographics
- Add/Edit/Delete buttons for CRUD operations

**Permission Requirements:**
- Viewers: Read-only access
- Analysts: Read access to data
- POPDEV Managers: Can create/edit/delete
- Data Encoders: Limited to assigned barangays

---

## 📤 Data Import Module

### Excel & CSV File Upload

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Data Import | `/data-import` | DataImportController | index | Show import page |
| Encoder Dashboard | `/data-import/encoder` | DataImportController | encoderDashboard | Encoder-specific view |
| Upload Form | `/data-import/upload` | DataImportController | uploadForm | File upload UI |
| Handle Upload | `/api/data-import/upload` (POST) | DataImportController | handleUpload | Process file |
| Import Details | `/api/import/{id}` (GET) | DataImportController | getImportDetails | Get import status |
| Import Stats | `/api/import-stats` (GET) | DataImportController | getImportStats | Overall statistics |
| Retry Import | `/api/import/{id}/retry` (POST) | DataImportController | retryImport | Re-process failed import |
| Import History | `/api/import-history` (GET) | DataImportController | getUploadHistory | Past imports |
| Download Template | `/api/data-import/template` (GET) | DataImportController | downloadTemplate | Excel template |

**Workflow:**
```
User selects file
      ↓
Select barangay
      ↓
POST /api/data-import/upload
      ↓
Backend validates & parses Excel
      ↓
Insert/update database
      ↓
Show results (success/errors)
      ↓
Display in import history
```

**Accepted Formats:**
- Excel (.xlsx, .xls)
- CSV files

**Required Columns:**
- name
- weight
- barangay
- address
- salary
- family_members

---

## 🏘️ Barangay Records Module

### Health & Demographic Records

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Barangay Records | `/barangay-records` | BarangayRecordsController | index | Show barangay records |
| Health Metrics | `/api/health-metrics/{barangayId}` (GET) | BarangayRecordsController | getHealthMetrics | Immunization, mortality data |
| Malnutrition Data | `/api/malnutrition-data/{barangayId}` (GET) | BarangayRecordsController | getMalnutritionData | Wasting, stunting, underweight |
| Water & Sanitation | `/api/water-sanitation/{barangayId}` (GET) | BarangayRecordsController | getWaterSanitation | Access percentages |

**Data Displayed:**
- Immunization coverage chart
- Maternal mortality rate
- Infant mortality rate
- Under-5 mortality rate
- Malnutrition prevalence
- Water & sanitation access

**Selection:**
- Dropdown to select barangay
- Data updates dynamically
- Charts re-render for selected barangay

---

## 📚 Knowledge Management Module

### Document Repository

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Knowledge Base | `/knowledge-management` | KnowledgeManagementController | index | Show documents |
| Get Documents | `/api/documents` (GET) | KnowledgeManagementController | getDocuments | List documents (filtered) |
| Get Categories | `/api/categories` (GET) | KnowledgeManagementController | getCategories | Category list with counts |
| Upload Document | `/api/document/upload` (POST) | KnowledgeManagementController | uploadDocument | Upload new document |

**Features:**
- Upload documents (PDF, DOC, XLS, etc.)
- Organize by category
- View document statistics
- Recent documents table
- Category breakdown cards

**Permissions:**
- Managers: Can upload
- All roles: Can view

---

## 🤖 ML Analytics Module

### Machine Learning Predictions

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| ML Analytics | `/ml-analytics` | MLAnalyticsController | index | Show analytics page |
| Risk Predictions | `/api/risk-predictions` (GET) | MLAnalyticsController | getRiskPredictions | Household vulnerability scores |
| Population Forecast | `/api/population-forecast` (GET) | MLAnalyticsController | getPopulationForecast | Population trends prediction |
| Clustering Results | `/api/clustering-results` (GET) | MLAnalyticsController | getClusteringResults | K-means clusters (5 groups) |
| Feature Importance | `/api/feature-importance` (GET) | MLAnalyticsController | getFeatureImportance | Important factors analysis |

**ML Models Used:**
1. **Decision Tree** - Risk classification
2. **Random Forest** - Improved predictions
3. **K-Means Clustering** - Household grouping
4. **Regression Analysis** - Population forecasting

**Charts Displayed:**
- Risk score distribution (scatter)
- Population forecast (line)
- Clustering visualization
- Feature importance (horizontal bar)

---

## 🎯 Decision Support Module

### Strategic Analysis & Policy Support

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Decision Support | `/decision-support` | DecisionSupportController | index | Show dashboards |
| Get Dashboards | `/api/dashboards` (GET) | DecisionSupportController | getDashboards | Dashboard list |
| Get Reports | `/api/reports` (GET) | DecisionSupportController | getReports | Generated reports |
| Policy Simulation | `/api/policy-simulation` (POST) | DecisionSupportController | runPolicySimulation | Run what-if scenarios |

**Displays:**
- Executive dashboards
- Key Performance Indicators
- Population growth tracking
- Poverty rate monitoring
- Health coverage metrics
- Education access rates

**Available Reports:**
- Monthly progress reports
- Annual development goals
- Risk assessments

---

## 🔐 Security & Governance Module (Admin Only)

### User & System Management

| Page | Route | Controller | Method | Purpose |
|------|-------|-----------|--------|---------|
| Security Panel | `/security-governance` | SecurityGovernanceController | index | Admin dashboard |
| Get Users | `/api/users` (GET) | SecurityGovernanceController | getUsers | List all users |
| Create User | `/api/user/create` (POST) | SecurityGovernanceController | createUser | Add new user |
| Update User | `/api/user/update/{id}` (PUT) | SecurityGovernanceController | updateUser | Change user role |
| Delete User | `/api/user/delete/{id}` (DELETE) | SecurityGovernanceController | deleteUser | Remove user |
| Audit Logs | `/api/audit-logs` (GET) | SecurityGovernanceController | getAuditLogs | Activity history |
| Security Status | `/api/security-status` (GET) | SecurityGovernanceController | getSecurityStatus | System security metrics |
| Role Permissions | `/api/role-permissions` (GET) | SecurityGovernanceController | getRolePermissions | Role definitions |
| User Permissions | `/api/user/{id}/permissions` (PUT) | SecurityGovernanceController | updateUserPermissions | Set user permissions |
| Security Metrics | `/api/security-metrics` (GET) | SecurityGovernanceController | getSystemSecurityMetrics | Security statistics |

**Access Control:**
- Only City Administrator role has access
- Cannot delete own account
- All actions logged in audit trail

**User Roles Available:**
1. **City Administrator** - Full system access
2. **POPDEV Manager** - Data and document management
3. **Barangay Data Encoder** - Data entry only
4. **Analyst** - Read-only analytics
5. **Viewer** - Public dashboards only

---

## 🔄 Data Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    USER INTERFACE                           │
│  - Sidebar Navigation (data-page attributes)               │
│  - Page Content Area (dynamically loaded)                  │
│  - Modal Forms (create/edit)                               │
└────────────────────┬────────────────────────────────────────┘
                     │
        ┌────────────┼────────────┐
        │            │            │
    Navigation    Page Load    Form Submit
        │            │            │
        ▼            ▼            ▼
┌────────────────────────────────────────┐
│    app.js (JavaScript Application)     │
│  - navigateTo(page)                   │
│  - loadPage(page)                     │
│  - Fetch API endpoints                │
│  - Render with Chart.js              │
└────────────────────┬───────────────────┘
                     │
        ┌────────────┼────────────┐
        │            │            │
    GET Request  POST Request  PUT/DELETE
        │            │            │
        ▼            ▼            ▼
┌────────────────────────────────────────┐
│    PHP Router (config/Router.php)      │
│  - Route matching                      │
│  - Method dispatching                  │
└────────────────────┬───────────────────┘
                     │
        ┌────────────┼────────────┐
        │            │            │
   Controller    Middleware    Validation
        │            │            │
        ▼            ▼            ▼
┌────────────────────────────────────────┐
│    Controllers (app/Controllers/)      │
│  - Process request logic               │
│  - Call models for data                │
│  - Return JSON response                │
└────────────────────┬───────────────────┘
                     │
        ┌────────────┼────────────┐
        │            │            │
     Query       Insert       Update
        │            │            │
        ▼            ▼            ▼
┌────────────────────────────────────────┐
│    Models (app/Models/)                │
│  - Database operations                 │
│  - Data validation                     │
└────────────────────┬───────────────────┘
                     │
                 Database
                     │
          MySQL (calamba_popdev)
```

---

## 📱 Page Navigation Map

```
LOGIN PAGE (/)
    │
    └─→ DASHBOARD (/dashboard)
            │
            ├─→ DATA MANAGEMENT (/data-management)
            │       │
            │       ├─→ View Barangays
            │       ├─→ View Households
            │       └─→ View Individuals
            │
            ├─→ DATA IMPORT (/data-import)
            │       ├─→ Upload Files
            │       ├─→ View History
            │       └─→ Download Template
            │
            ├─→ BARANGAY RECORDS (/barangay-records)
            │       ├─→ Health Metrics
            │       ├─→ Malnutrition Data
            │       └─→ Water & Sanitation
            │
            ├─→ KNOWLEDGE MANAGEMENT (/knowledge-management)
            │       ├─→ Upload Document
            │       ├─→ View Documents
            │       └─→ View Categories
            │
            ├─→ ML ANALYTICS (/ml-analytics)
            │       ├─→ Risk Predictions
            │       ├─→ Population Forecast
            │       ├─→ Clustering Results
            │       └─→ Feature Importance
            │
            ├─→ DECISION SUPPORT (/decision-support)
            │       ├─→ Dashboards
            │       ├─→ Reports
            │       └─→ KPIs
            │
            └─→ SECURITY & GOVERNANCE (/security-governance)
                    ├─→ Manage Users
                    ├─→ Role Permissions
                    ├─→ Audit Logs
                    └─→ Security Status

LOGOUT (/api/logout)
    └─→ RETURN TO LOGIN
```

---

## 🔗 API Endpoint Summary

### Base URL
```
http://localhost:8000
or
http://localhost/CityOfCalambaDev/public
```

### Authentication Endpoints
```
GET  /                      - Login page
POST /api/login            - Authenticate user
POST /api/logout           - End session
POST /api/register         - Create account
GET  /api/user-profile     - Get user info
```

### Dashboard & Management
```
GET  /dashboard                    - Dashboard
GET  /api/barangays               - All barangays
GET  /api/households              - All households
GET  /api/individuals             - All individuals
GET  /api/audit-logs              - Activity logs
GET  /api/data-quality            - Quality metrics
```

### Barangay Operations
```
POST   /api/barangay/create       - Create barangay
PUT    /api/barangay/update/{id}  - Update barangay
DELETE /api/barangay/delete/{id}  - Delete barangay
```

### Data Import
```
GET  /data-import                 - Import page
GET  /data-import/encoder         - Encoder dashboard
GET  /data-import/upload          - Upload form
POST /api/data-import/upload      - Process upload
GET  /api/import/{id}             - Get import status
GET  /api/import-stats            - Import statistics
POST /api/import/{id}/retry       - Retry failed import
GET  /api/import-history          - Past imports
GET  /api/data-import/template    - Download template
```

### Health Records
```
GET /api/health-metrics/{barangayId}     - Health data
GET /api/malnutrition-data/{barangayId}  - Malnutrition data
GET /api/water-sanitation/{barangayId}   - Water/sanitation data
```

### Knowledge Management
```
GET  /knowledge-management        - Knowledge page
GET  /api/documents              - List documents
GET  /api/categories             - List categories
POST /api/document/upload        - Upload document
```

### ML Analytics
```
GET /ml-analytics                - Analytics page
GET /api/risk-predictions        - Risk scores
GET /api/population-forecast     - Population trends
GET /api/clustering-results      - Cluster data
GET /api/feature-importance      - Feature analysis
```

### Decision Support
```
GET  /decision-support           - Decision page
GET  /api/dashboards            - Dashboard list
GET  /api/reports               - Report list
POST /api/policy-simulation     - Run simulation
```

### Security & Governance
```
GET    /security-governance              - Security page
GET    /api/users                       - User list
POST   /api/user/create                 - Create user
PUT    /api/user/update/{id}            - Update user
DELETE /api/user/delete/{id}            - Delete user
GET    /api/audit-logs                  - Audit trail
GET    /api/security-status             - Security metrics
GET    /api/role-permissions            - Role definitions
PUT    /api/user/{id}/permissions      - Set permissions
GET    /api/security-metrics            - Security statistics
```

---

## 🔑 Permission Matrix

| Role | Dashboard | Data Mgmt | Import | Records | Knowledge | ML | Decision | Security |
|------|-----------|-----------|--------|---------|-----------|----|---------|----|
| City Admin | ✓ | ✓ Edit | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| POPDEV Mgr | ✓ | ✓ Edit | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ |
| Encoder | Limited | Own Only | ✓ Own | Own | ✗ | ✗ | ✗ | ✗ |
| Analyst | ✓ | View | ✗ | View | View | ✓ | ✓ | ✗ |
| Viewer | Limited | ✗ | ✗ | ✗ | ✗ | ✗ | Limited | ✗ |

---

## 🧪 Testing Connections

### Quick Connection Test

1. **Start server:**
   ```bash
   cd c:\xampp\htdocs\CityOfCalambaDev\public
   php -S localhost:8000
   ```

2. **Test login:**
   - Go to http://localhost:8000
   - Email: admin@calamba.gov.ph
   - Password: DefaultPass@123

3. **Test dashboard:**
   - Should load with statistics
   - Charts should render
   - Recent updates should appear

4. **Test each module:**
   - Click each sidebar menu item
   - Verify page loads
   - Check console for errors (F12)
   - Verify data appears

5. **Test API directly:**
   ```bash
   # In PowerShell
   curl http://localhost:8000/api/barangays -Headers @{"Authorization"="Bearer YOUR_TOKEN"}
   ```

---

## 🐛 Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| 404 errors | Route not mapped | Check Router.php routes |
| Blank pages | JavaScript error | Check browser console (F12) |
| Data not loading | API endpoint not responding | Verify controller methods |
| CSS missing | Asset path incorrect | Check helpers.php asset() function |
| Session expired | Login token invalid | Re-login |
| Permission denied | Insufficient role | Check role in user table |
| Database connection failed | MySQL not running | Start MySQL in XAMPP |

---

## 📝 Page Connection Checklist

- [x] Login route configured
- [x] Dashboard page connected
- [x] Navigation sidebar linked
- [x] All API endpoints defined
- [x] Controllers implement all methods
- [x] JavaScript page loaders created
- [x] Charts render correctly
- [x] Data flows from API to UI
- [x] Permission checks in place
- [x] Error handling implemented
- [x] Audit logging active
- [x] Session management working

---

## 🎯 Next Steps

1. **Run the application** - Follow Quick Start guide in README
2. **Test each module** - Click through all pages
3. **Verify permissions** - Try different user roles
4. **Monitor logs** - Check audit trails
5. **Backup data** - Regular database backups
6. **Update documentation** - Keep notes on customizations

---

**Last Updated:** April 29, 2026
**Version:** 1.0
**Status:** ✅ All Pages Connected
