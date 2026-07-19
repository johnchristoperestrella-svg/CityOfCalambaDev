# Calamba PopDev - Performance Optimization Checklist

Use this checklist to track implementation of all performance fixes.

---

## PHASE 1: CRITICAL FIXES (2-3 Hours Total)

These 4 fixes solve 80% of performance problems. Start here.

### 1.1 Fix N+1 Query in AnalyticsController
- [ ] File: `app/Controllers/AnalyticsController.php`
- [ ] Lines: 28-35
- [ ] Change: Replace loop of `getByImportId()` with single JOIN query
- [ ] Expected: 91% faster (550ms → 50ms)
- [ ] Testing:
  - [ ] Load analytics dashboard
  - [ ] Verify database queries reduced from 10+ to 1-2
  - [ ] Check load time < 500ms

### 1.2 Fix N+1 Query in MLAnalyticsController  
- [ ] File: `app/Controllers/MLAnalyticsController.php`
- [ ] Lines: 27-35 (index method)
- [ ] Lines: 64-75 (getRiskPredictions method)
- [ ] Lines: 91-98 (getClusteringResults method)
- [ ] Change: Add pagination to getAll() calls (default to page 1, limit 50-500)
- [ ] Expected: 100× faster for large datasets
- [ ] Testing:
  - [ ] Load ML analytics dashboard
  - [ ] Verify response < 500ms
  - [ ] Check pagination working correctly
  - [ ] Verify total counts accurate

### 1.3 Fix Loop-Based Individual Record Creation
- [ ] File: `app/Controllers/DataImportController.php`
- [ ] Lines: 185-200 (processImportData method)
- [ ] Change: Replace loop with batch insert methods
- [ ] Create new methods:
  - [ ] `batchInsertHouseholds()` 
  - [ ] `batchInsertIndividuals()`
  - [ ] `createIndividualsForImport()`
- [ ] Expected: 2,500× faster (100s → 40ms)
- [ ] Testing:
  - [ ] Import 100 records (should take <500ms)
  - [ ] Import 1,000 records (should take <5s)
  - [ ] Verify all data correctly inserted
  - [ ] Check memory usage during import

### 1.4 Fix PHP Analytics Calculations
- [ ] File: `app/Models/Analytics.php`
- [ ] Lines: 70-140 (getImportData, calculateAnalytics, grouping methods)
- [ ] Change: Replace 6 PHP loops with optimized SQL queries
- [ ] Refactor:
  - [ ] Use SQL UNION ALL to get all distributions in one query
  - [ ] Use SQL COUNT/AVG/GROUP BY instead of array operations
  - [ ] Create helper method `formatDistribution()` for simple formatting
- [ ] Expected: 90% faster (500ms → 50ms)
- [ ] Testing:
  - [ ] Generate analytics for 1,000 household import
  - [ ] Verify response time < 500ms
  - [ ] Validate all metrics calculated correctly
  - [ ] Check memory usage < 1MB

---

## PHASE 2: HIGH PRIORITY FIXES (1-2 Hours Total)

These fixes provide additional 50% improvement.

### 2.1 Add Database Indexes
- [ ] File: Create migration or run manually in `config/database.php` init
- [ ] Run SQL:
  - [ ] Foreign key indexes (8 indexes)
  - [ ] Filter indexes (3 indexes)
  - [ ] Composite indexes (3 indexes)
  - [ ] See: OPTIMIZATION_CODE_EXAMPLES.md section "HIGH PRIORITY FIX #1"
- [ ] Verify:
  - [ ] `SHOW INDEX FROM individuals;` shows indexes
  - [ ] `SHOW INDEX FROM households;` shows indexes
  - [ ] Query execution times reduced 20-100×
- [ ] Testing:
  - [ ] Before/after query timing comparison
  - [ ] Run EXPLAIN on slow queries to verify index usage
  - [ ] Dashboard load time should improve significantly

### 2.2 Fix Dashboard Pagination
- [ ] File: `app/Controllers/DashboardController.php`
- [ ] Lines: 26-35
- [ ] Change:
  - [ ] Load only top 10 individuals (not all)
  - [ ] Load only top 10 households (not all)
  - [ ] Get total counts from database COUNT queries
  - [ ] Use `getTotalCount()` methods instead of `count(array)`
- [ ] Expected: 99.9% less memory
- [ ] Testing:
  - [ ] Dashboard loads in <500ms
  - [ ] Memory usage drops from 5-10MB to <1MB
  - [ ] Totals are accurate (use COUNT, not array length)
  - [ ] Recent items displayed correctly

### 2.3 Fix env() File I/O Performance
- [ ] File: `config/helpers.php`
- [ ] Lines: 8-22
- [ ] Change: Cache env file parsing instead of re-reading every call
- [ ] Implementation:
  - [ ] Read .env file once at application start
  - [ ] Store in static array `$envCache`
  - [ ] Subsequent calls use array lookup (0.1ms vs 10ms)
- [ ] Expected: 99% faster (for calls 2+), 100ms saved per request
- [ ] Testing:
  - [ ] Verify env() function works correctly
  - [ ] Check that changes to .env require app restart (expected)
  - [ ] Profile application startup time

### 2.4 Implement Basic Caching (Optional, Medium Priority)
- [ ] Install Redis or use APCu
- [ ] Create cache helper functions:
  - [ ] `cache_get($key)`
  - [ ] `cache_put($key, $value, $ttl)`
  - [ ] `cache_forget($key)`
- [ ] Cache these data (with TTLs):
  - [ ] Barangays list (24 hours)
  - [ ] Health metrics (1 hour)
  - [ ] Analytics summaries (30 minutes)
- [ ] Clear cache on data modifications
- [ ] Expected: 80% improvement for repeat visitors
- [ ] Testing:
  - [ ] First page load: same as before
  - [ ] Second load: <100ms (cache hit)
  - [ ] Verify cache invalidation works

---

## PHASE 3: MEDIUM PRIORITY FIXES (1-2 Hours Total)

Nice-to-have improvements.

### 3.1 Minify and Compress Assets
- [ ] CSS:
  - [ ] Minify `public/css/style.css`
  - [ ] Enable gzip compression in .htaccess
- [ ] JavaScript:
  - [ ] Minify `public/js/app.js`
  - [ ] Minify all modules in `public/js/modules/`
- [ ] Add HTTP headers:
  - [ ] `Cache-Control: public, max-age=86400` for assets
- [ ] Expected: 85-90% reduction in transfer size
- [ ] Testing:
  - [ ] Verify CSS/JS still works
  - [ ] Check file sizes (should be 30-50KB for CSS instead of 200KB)
  - [ ] Verify gzip compression working in DevTools

### 3.2 Add ML Model Sampling
- [ ] File: `app/Controllers/MLAnalyticsController.php`
- [ ] Lines: 100-150 (trainModel method)
- [ ] Change:
  - [ ] Sample 500-1,000 households instead of all
  - [ ] Run full analysis async in background
  - [ ] Show "based on sample" note
- [ ] Expected: Faster response even for large barangays
- [ ] Testing:
  - [ ] Model training < 1 second
  - [ ] Results accurate (based on sample)
  - [ ] Note shown to users

### 3.3 Cache Router Pattern Matches
- [ ] File: `config/Router.php`
- [ ] Lines: 40-55 (dispatch and matchRoute methods)
- [ ] Change:
  - [ ] Compile regex patterns once at startup
  - [ ] Cache route matches in array
  - [ ] Skip regex matching for exact matches first
- [ ] Expected: 5-10ms saved per request
- [ ] Testing:
  - [ ] All routes still work correctly
  - [ ] Profile routing overhead

### 3.4 Add HTTP Cache Headers
- [ ] Add middleware/filter to set headers:
  - [ ] `Cache-Control: public, max-age=3600` for dashboards
  - [ ] `Cache-Control: private, max-age=300` for user-specific data
  - [ ] `Cache-Control: public, max-age=86400` for assets
  - [ ] `ETag` and `Last-Modified` headers
- [ ] Expected: 40% load reduction for repeat visitors
- [ ] Testing:
  - [ ] Check headers in DevTools Network tab
  - [ ] Verify browser caching working
  - [ ] Second visit loads faster

---

## TESTING & VALIDATION

After each phase, run these tests:

### Performance Benchmarks
- [ ] Dashboard load: Target <500ms
- [ ] Analytics page: Target <1s
- [ ] ML predictions: Target <500ms
- [ ] Data import 1,000 rows: Target <5s
- [ ] Database queries per page: Target <50
- [ ] Memory per request: Target <2MB
- [ ] Concurrent users support: Target 500+

### Functional Tests
- [ ] Dashboard displays all data correctly
- [ ] Analytics calculations are accurate
- [ ] ML predictions working
- [ ] Data imports complete successfully
- [ ] All page loads functional
- [ ] No broken links or errors
- [ ] Permission checks working
- [ ] Pagination working correctly

### Database Tests
- [ ] Indexes created successfully
- [ ] Queries using indexes (check EXPLAIN)
- [ ] No N+1 query patterns
- [ ] Connection pool not exhausted
- [ ] Transaction integrity maintained

### Load Tests
- [ ] 10 concurrent users: No errors
- [ ] 50 concurrent users: <1s response time
- [ ] 100 concurrent users: <2s response time
- [ ] 500 concurrent users: System stable
- [ ] Memory usage stays constant
- [ ] CPU usage reasonable

### Tools to Use
```bash
# MySQL query analysis
EXPLAIN SELECT ... \G

# PHP profiling
xdebug_print_function_stack();
xdebug_time_index();

# Load testing
Apache Bench: ab -n 1000 -c 100 http://localhost/dashboard

# Browser DevTools
- Network tab: Check load times
- Performance tab: Check rendering
- Memory tab: Check memory usage
```

---

## DEPLOYMENT CHECKLIST

Before deploying to production:

### Code Review
- [ ] All code follows project standards
- [ ] No debug code left in
- [ ] Error handling appropriate
- [ ] Security checks in place
- [ ] Comments added for complex logic

### Database
- [ ] Backup created before migration
- [ ] Indexes created and verified
- [ ] Data integrity checked
- [ ] Rollback plan documented

### Testing
- [ ] Unit tests passing
- [ ] Integration tests passing
- [ ] Performance benchmarks met
- [ ] No regressions in functionality
- [ ] Edge cases tested

### Deployment
- [ ] Code deployed to staging
- [ ] Smoke tests on staging pass
- [ ] Code deployed to production
- [ ] Performance monitoring enabled
- [ ] User communication prepared
- [ ] Rollback plan ready

### Post-Deployment
- [ ] Monitor error logs (24 hours)
- [ ] Monitor performance metrics
- [ ] Verify all features working
- [ ] Gather user feedback
- [ ] Document any issues found

---

## Effort Estimation

### Phase 1: Critical Fixes
| Fix | Effort | Notes |
|-----|--------|-------|
| N+1 Analytics | 30 min | Straightforward JOIN |
| N+1 ML | 45 min | Multiple places, pagination |
| Loop Insert | 20 min | Batch insert logic |
| PHP Calcs | 60 min | Complex refactor |
| Testing | 30 min | Validate all works |
| **Total** | **~3 hours** | |

### Phase 2: High Priority Fixes
| Fix | Effort | Notes |
|-----|--------|-------|
| Database Indexes | 10 min | Run SQL statements |
| Dashboard Pagination | 15 min | Update controller |
| env() Caching | 10 min | Simple static cache |
| Basic Caching (optional) | 90 min | Set up Redis/APCu |
| Testing | 30 min | Validation |
| **Total** | **~2-3 hours** | (1-2 without caching) |

### Phase 3: Medium Priority
| Fix | Effort | Notes |
|-----|--------|-------|
| Asset Minification | 15 min | One-time build step |
| ML Sampling | 20 min | Add logic |
| Router Caching | 20 min | Pattern compilation |
| HTTP Headers | 10 min | Middleware |
| Testing | 30 min | Validation |
| **Total** | **~1.5 hours** | |

**Total for All Phases: ~7 hours developer time**

---

## Risk Mitigation

### If Something Goes Wrong

**Problem:** Dashboard shows wrong totals after pagination fix
```
Solution: Use COUNT query instead of array length
Verify: SELECT COUNT(*) FROM individuals returns correct total
```

**Problem:** Data import fails with batch insert
```
Solution: Check transaction handling, may need to split into smaller batches
Fallback: Revert to individual inserts, re-run optimization later
```

**Problem:** Cache gets stale and shows old data
```
Solution: Implement cache invalidation on data modification
Example: Clear barangay cache when barangay is updated
```

**Problem:** Indexes don't improve performance as expected
```
Solution: Run EXPLAIN to verify index is being used
Check: Look for "Using index" in EXPLAIN output
```

---

## Success Metrics

Track these before and after:

### Response Time
```
Before: Dashboard 2-3s, ML 10s, Import 30s
After:  Dashboard 300-500ms, ML 200-500ms, Import 2-5s
Goal:   3-5x faster overall
```

### Database Efficiency
```
Before: 500+ queries per page load
After:  <50 queries per page load
Goal:   10× fewer queries
```

### Memory Usage
```
Before: 5-10MB per request
After:  1-2MB per request
Goal:   75-80% reduction
```

### Scalability
```
Before: 100 concurrent users max
After:  500-1000 concurrent users
Goal:   5-10× more users supported
```

### Cache Efficiency
```
Before: N/A
After:  80%+ cache hit rate for repeat visits
Goal:   40% faster for returning users
```

---

## Sign-Off

- [ ] Technical Lead: Review and approve optimization plan
- [ ] Database Admin: Approve index creation
- [ ] Project Manager: Approve 3-week optimization sprint
- [ ] QA Lead: Approve testing approach
- [ ] DevOps: Approve deployment process

**Approval Date:** ___________

**Expected Completion:** ___________

**Estimated Cost Savings:** $$$

---

## Appendix: Quick Reference

### Key Files to Modify
1. `app/Controllers/AnalyticsController.php` - Fix N+1
2. `app/Controllers/MLAnalyticsController.php` - Fix N+1 & pagination
3. `app/Controllers/DataImportController.php` - Fix batch insert
4. `app/Models/Analytics.php` - Fix PHP calculations
5. `config/database.php` - Add indexes
6. `config/helpers.php` - Fix env() caching
7. `app/Controllers/DashboardController.php` - Fix pagination

### Performance Analysis Documents
- `PERFORMANCE_ANALYSIS_DETAILED.md` - Full technical analysis
- `OPTIMIZATION_CODE_EXAMPLES.md` - Specific code fixes
- `PERFORMANCE_ANALYSIS_EXECUTIVE_SUMMARY.md` - For stakeholders

### Performance Testing Tools
- Apache Bench (load testing)
- MySQL EXPLAIN (query optimization)
- PHP xdebug (profiling)
- Browser DevTools (frontend)
- New Relic (APM - optional)

---

*Last Updated: June 5, 2026*  
*Next Review: After Phase 1 completion*
