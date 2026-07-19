# 📊 Excel Upload & Analytics System - Implementation Complete

**Date**: June 3, 2026  
**Status**: ✅ Ready for Use

---

## 🎯 System Overview

This system enables users to upload Excel files containing household and individual data. The system automatically:

1. **Reads** the uploaded Excel file
2. **Parses** the data and validates it
3. **Stores** all records in the database (linked to the import)
4. **Analyzes** the data to generate comprehensive analytics
5. **Displays** analytics through an interactive dashboard
6. **Exports** analytics in JSON or CSV formats

### Key Features

✅ **Excel Data Import** - Upload Excel files with household and individual data  
✅ **Automatic Analytics Generation** - Metrics calculated immediately after import  
✅ **Related Data Tracking** - All data linked to original Excel import  
✅ **Multi-level Analytics** - Individual imports, barangay comparisons, system-wide summaries  
✅ **Data Distribution Charts** - Gender, education, health status, socioeconomic breakdowns  
✅ **Key Findings & Recommendations** - AI-generated insights based on data  
✅ **Export Functionality** - Download reports in JSON or CSV  
✅ **Role-based Access** - Different users see different analytics based on permissions  

---

## 🏗️ Architecture

### Database Tables (New)

#### `import_analytics`
Stores computed analytics for each Excel import:
- **Fields**: Total records, households, individuals, averages, distributions (JSON)
- **Key Metrics**: Low-income percentage, at-risk health percentage, key findings, recommendations
- **Linked to**: `data_imports` table (one-to-one)

#### `analytics_comparison`
Stores comparison data between two imports:
- **Fields**: Changes in households, individuals, socioeconomic status, health status
- **Use Case**: Track population changes over time between imports

#### Updated Tables
- `data_imports` - Added analytics summary fields
- `households` - Added `import_id` to track which Excel import created the record
- `individuals` - Added `import_id` to track which Excel import created the record

### Controllers

#### `AnalyticsController`
**Location**: `app/Controllers/AnalyticsController.php`

**Methods**:
- `index()` - Dashboard with all analytics
- `viewByImport($importId)` - Detailed view for specific import
- `viewByBarangay($barangayId)` - Analytics for a barangay
- `compareImports()` - Compare two imports (API)
- `getSummary()` - Overall summary (API)
- `getMetrics($importId)` - Get chart data (API)
- `exportAnalytics()` - Export as JSON/CSV

#### `DataImportController` (Updated)
**Changes Made**:
- Added `$analyticsModel` property
- Updated `handleUpload()` to generate analytics after import
- Updated `processImportData()` to link records to import

### Models

#### `Analytics` Model
**Location**: `app/Models/Analytics.php`

**Key Methods**:
- `generateAnalyticsForImport($importId, $barangayId)` - Main analytics generation
- `calculateAnalytics()` - Calculate all metrics
- `getByImportId($importId)` - Retrieve analytics for import
- `getByBarangay($barangayId)` - Get all analytics for barangay
- `compareImports($id1, $id2, $barangayId)` - Compare two imports

**Calculated Metrics**:
1. **Basic Stats**: Total records, households, individuals, averages
2. **Distributions**: 
   - Gender (Male/Female/Other)
   - Education (4 levels)
   - Health Status (3 levels)
   - Socioeconomic (5 levels)
3. **Risk Indicators**:
   - Low-income households count & percentage
   - At-risk health individuals count & percentage
4. **Insights**:
   - Key findings (5-7 auto-generated)
   - Recommendations (3-5 auto-generated based on thresholds)

### Views

#### Analytics Dashboard (`analytics/dashboard.php`)
- Summary cards (total households, individuals, avg size, imports count)
- Recent analytics table with all imports
- Key findings and recommendations sections
- Export and details action buttons

#### Import Details View (`analytics/view-import.php`)
- Detailed analytics for single import
- Visual distribution charts with progress bars
- Gender, education, health status, socioeconomic breakdowns
- Auto-generated key findings and recommendations
- Export options

#### Barangay Analytics View (`analytics/view-barangay.php`)
- Timeline of all imports for a barangay
- Comparison between latest two imports
- Shows differences in households, individuals, percentages
- Color-coded increase/decrease indicators

### API Endpoints

#### Analytics Endpoints
```
GET  /analytics                          # Dashboard
GET  /analytics/import/{importId}        # View specific import analytics
GET  /analytics/barangay/{barangayId}    # View barangay analytics
GET  /analytics/export?import_id=X&format=json|csv  # Export analytics
POST /api/analytics/compare              # Compare two imports
GET  /api/analytics/summary              # Get overall summary
GET  /api/analytics/metrics/{importId}   # Get chart data
```

---

## 🔄 Data Flow

### Step-by-Step Process

```
1. User uploads Excel file
   ↓
2. File validation & parsing (ExcelParser)
   ↓
3. Create data_imports record
   ↓
4. Process each row:
   - Create household with import_id
   - Create individuals with import_id
   ↓
5. Calculate analytics:
   - Aggregate data by import_id
   - Calculate all metrics
   - Generate findings & recommendations
   ↓
6. Store in import_analytics table
   ↓
7. Display analytics in dashboard
```

### Data Relationships

```
data_imports (1)
    ├── households (N) -- linked via import_id
    │   ├── individuals (N) -- linked via import_id
    └── import_analytics (1) -- contains all calculated metrics
```

---

## 📊 Analytics Calculations

### Key Findings Examples
- "Average household size: 4.2 members"
- "Average age of population: 38.5 years"
- "Low-income households: 42.5%"
- "Population with health concerns: 18.3%"
- "Middle-class households: 35.2%"

### Recommendations Examples
- "High poverty rate detected: Consider targeted livelihood programs" (if >30%)
- "Significant health risks: Increase preventive health campaigns" (if >20%)
- "Low education levels: Focus on adult literacy programs" (if >15%)
- "Young population: Invest in youth programs and education" (if avg age <25)
- "Aging population: Focus on senior care and pension programs" (if avg age >50)

---

## 🚀 Usage Guide

### For Data Encoders

1. **Upload Excel File**
   - Navigate to `/data-import/upload`
   - Select barangay
   - Choose Excel file
   - System will automatically generate analytics

2. **View Analytics**
   - Go to `/analytics` dashboard
   - Click "View Details" on any import
   - See comprehensive breakdown of uploaded data

3. **Export Report**
   - Click "Export as JSON" or "Export as CSV"
   - Download for offline analysis

### For Administrators

1. **View All Analytics**
   - Access `/analytics` (admin sees all imports)
   - See system-wide summary statistics

2. **Compare Barangays**
   - Click on specific barangay analytics
   - See timeline and improvements over time

3. **Compare Imports**
   - Use API endpoint to compare two imports
   - Track population changes

---

## 🔐 Permission Requirements

### Required Permissions
- `upload_excel` - Upload Excel files
- `view_analytics` - View analytics dashboard

### Role-Based Access
- **City Administrator**: Sees all analytics
- **POPDEV Manager**: Sees assigned barangay analytics
- **Barangay Data Encoder**: Sees only their uploads
- **Analyst**: Sees all analytics (read-only)
- **Viewer**: Sees public summaries

---

## 📁 File Structure

```
project/
├── app/
│   ├── Controllers/
│   │   ├── AnalyticsController.php       [NEW]
│   │   ├── DataImportController.php      [UPDATED]
│   │   └── ...
│   └── Models/
│       ├── Analytics.php                 [NEW]
│       ├── Household.php                 [UPDATED]
│       ├── Individual.php                [UPDATED]
│       └── ...
├── database/
│   └── migrations/
│       ├── 001_create_initial_tables.sql
│       └── 002_add_analytics_and_import_tracking.sql  [NEW]
├── resources/
│   └── views/
│       └── analytics/                    [NEW FOLDER]
│           ├── dashboard.php             [NEW]
│           ├── view-import.php           [NEW]
│           └── view-barangay.php         [NEW]
├── public/
│   └── index.php                         [UPDATED - routes added]
└── migrate.php                           [NEW - migration runner]
```

---

## 🛠️ Installation & Setup

### 1. Database Migration
```bash
cd c:\xampp\htdocs\CityOfCalambaDev
php migrate.php
```

✅ **Already Applied** - 12 SQL statements executed

### 2. Verify Installation
```
✓ import_analytics table created
✓ analytics_comparison table created
✓ import_id column added to households
✓ import_id column added to individuals
✓ data_imports updated with analytics fields
```

### 3. Access Analytics

Navigate to: `http://localhost:8080/analytics`

---

## 📈 Example Analytics Output

### Summary Statistics
```
Total Records:           2,450
Total Households:        580
Total Individuals:       2,450
Avg Household Size:      4.22
Average Age:             38.5 years

Low-Income Households:   215 (37.1%)
At-Risk Individuals:     382 (15.6%)
```

### Gender Distribution
- Male: 1,225 (50.0%)
- Female: 1,200 (49.0%)
- Other: 25 (1.0%)

### Education Distribution
- No Formal Education: 145 (5.9%)
- Primary: 620 (25.3%)
- Secondary: 1,450 (59.2%)
- Tertiary: 235 (9.6%)

### Health Status
- Healthy: 2,068 (84.4%)
- At-Risk: 315 (12.9%)
- Chronically Ill: 67 (2.7%)

### Socioeconomic Status
- Low: 185 (31.9%)
- Lower Middle: 85 (14.7%)
- Middle: 205 (35.3%)
- Upper Middle: 95 (16.4%)
- High: 10 (1.7%)

---

## 🔧 Customization

### Add New Metric Calculation
1. Open `app/Models/Analytics.php`
2. Add method like `calculateNewMetric($data)`
3. Add to `$analytics` array in `calculateAnalytics()`
4. Update database schema if storing permanently

### Modify Key Findings Logic
Edit `generateKeyFindings()` method in Analytics model to customize recommendations.

### Update Thresholds
Modify these values in `generateRecommendations()`:
- Low-income threshold: 30% (line ~285)
- Health risk threshold: 20% (line ~289)
- Education threshold: 15% (line ~293)

---

## 🐛 Troubleshooting

### Analytics Not Generating
1. Check database migration was applied
2. Verify import_id is set in households/individuals
3. Check error logs for SQL errors

### Charts Not Displaying
1. Verify JSON data is valid
2. Check browser console for JavaScript errors
3. Ensure CSS files are loading

### Export Failing
1. Verify file permissions in public/uploads
2. Check disk space available
3. Ensure PHP has write permissions

---

## 📞 Support

For issues or questions:
1. Check error logs in browser console
2. Review application error logs
3. Verify database connectivity

---

## ✅ Checklist

- [x] Database tables created
- [x] Analytics model implemented
- [x] Analytics controller created
- [x] Analytics views designed
- [x] Routes added to index.php
- [x] Data import updated to generate analytics
- [x] Models updated to track import_id
- [x] Migration script created and executed
- [x] Documentation completed

**All systems ready for production!** 🚀
