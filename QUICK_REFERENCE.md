# 🎨 Quick Reference - Beautiful UI Components

## 🚀 Quick Start

### Basic Page Template
```html
<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <h1>Page Title</h1>
    <div class="grid">
        <!-- Your content here -->
    </div>
</div>
```

---

## 🎯 Most Common Components

### 1. Button
```html
<button class="btn btn-primary">Click Me</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-outline">Outline</button>
<button class="btn btn-sm btn-primary">Small</button>
```

### 2. Card
```html
<div class="card">
    <div class="card-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-body">
        Your content here
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Action</button>
    </div>
</div>
```

### 3. Stat Card
```html
<div class="stat-card">
    <h4>Metric Name</h4>
    <div class="stat-value">1,234</div>
    <div class="stat-change">↑ 12% from last month</div>
</div>
```

### 4. Alert
```html
<div class="alert alert-success">✅ Success message</div>
<div class="alert alert-danger">❌ Error message</div>
<div class="alert alert-warning">⚠️ Warning message</div>
<div class="alert alert-info">ℹ️ Info message</div>
```

### 5. Badge
```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-danger">Danger</span>
```

### 6. Form Group
```html
<div class="form-group">
    <label for="name">Your Name</label>
    <input type="text" id="name" placeholder="Enter name">
</div>

<div class="form-row">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email">
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="tel" id="phone">
    </div>
</div>
```

### 7. Table
```html
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John</td>
            <td>john@example.com</td>
            <td><span class="badge badge-success">Active</span></td>
        </tr>
    </tbody>
</table>
```

### 8. Progress Bar
```html
<div style="margin-bottom: 15px;">
    <label>Completion: 75%</label>
    <div class="progress">
        <div class="progress-bar" style="width: 75%;"></div>
    </div>
</div>
```

### 9. Modal
```html
<div class="modal" id="modal-1">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Modal Title</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Modal content goes here</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline">Cancel</button>
            <button class="btn btn-primary">Save</button>
        </div>
    </div>
</div>
```

### 10. List Group
```html
<div class="list-group">
    <div class="list-group-item">Item 1</div>
    <div class="list-group-item active">Item 2 (Selected)</div>
    <div class="list-group-item">Item 3</div>
</div>
```

---

## 🎨 Utility Classes Cheat Sheet

### Spacing
| Class | Value |
|-------|-------|
| `mt-5, mt-10, mt-15, mt-20, mt-30` | margin-top |
| `mb-5, mb-10, mb-15, mb-20, mb-30` | margin-bottom |
| `px-10, px-20` | padding-left/right |
| `py-10, py-20` | padding-top/bottom |

### Text
| Class | Effect |
|-------|--------|
| `text-center` | text-align: center |
| `text-right` | text-align: right |
| `text-left` | text-align: left |
| `text-primary` | color: primary |
| `text-success` | color: success |
| `text-danger` | color: danger |
| `text-warning` | color: warning |

### Layout
| Class | Effect |
|-------|--------|
| `flex` | display: flex |
| `flex-center` | flex + center alignment |
| `flex-between` | flex + space-between |
| `flex-col` | flex + column direction |
| `gap-5, gap-10, gap-15, gap-20` | gap between items |

### Visibility
| Class | Effect |
|-------|--------|
| `hidden` | display: none |
| `visible` | display: block |
| `opacity-50` | opacity: 0.5 |
| `opacity-75` | opacity: 0.75 |

### Shadows & Borders
| Class | Effect |
|-------|--------|
| `shadow` | box-shadow: var(--shadow) |
| `shadow-md` | box-shadow: var(--shadow-md) |
| `shadow-lg` | box-shadow: var(--shadow-lg) |
| `rounded` | border-radius: 8px |
| `rounded-lg` | border-radius: 12px |

### Grid Layout
```html
<!-- 3 equal columns -->
<div class="grid">
    <div class="card">Column 1</div>
    <div class="card">Column 2</div>
    <div class="card">Column 3</div>
</div>

<!-- 2 columns (2:1 ratio) -->
<div class="grid" style="grid-template-columns: 2fr 1fr;">
    <div class="card">Wide Column</div>
    <div class="card">Narrow Column</div>
</div>

<!-- 4 columns -->
<div class="grid" style="grid-template-columns: repeat(4, 1fr);">
    <div class="card">Item 1</div>
    <div class="card">Item 2</div>
    <div class="card">Item 3</div>
    <div class="card">Item 4</div>
</div>
```

---

## 🎭 Colors (CSS Variables)

```css
--primary-color: #2563eb
--primary-light: #3b82f6
--primary-dark: #1e40af

--secondary-color: #7c3aed
--secondary-light: #a78bfa

--success-color: #10b981
--success-light: #34d399

--danger-color: #ef4444
--danger-light: #f87171

--warning-color: #f59e0b
--warning-light: #fbbf24

--info-color: #06b6d4

--dark: #1f2937
--light: #f9fafb
--light-dark: #f3f4f6
```

---

## 💡 Pro Tips

1. **Always use cards** for grouping related content
2. **Use stat-cards** for displaying metrics
3. **Use badges** for status indicators
4. **Use alerts** for important messages
5. **Use progress bars** for showing completion
6. **Use tables** for tabular data
7. **Use buttons** consistently with colors
8. **Use spacing utilities** for consistent margins
9. **Use flex classes** for layouts
10. **Use shadows** for depth

---

## 📱 Responsive Grid

The grid automatically adjusts:
- **Desktop**: Multiple columns
- **Tablet**: 2 columns
- **Mobile**: 1 column

No extra CSS needed!

---

## 🎯 Form Layout Examples

### Horizontal Form
```html
<div class="form-row">
    <div class="form-group">
        <label>First Name</label>
        <input type="text">
    </div>
    <div class="form-group">
        <label>Last Name</label>
        <input type="text">
    </div>
</div>
```

### Full Width Form
```html
<div class="form-group">
    <label>Full Name</label>
    <input type="text" style="width: 100%;">
</div>
```

### Complex Form
```html
<div class="form-group">
    <label>Select Option</label>
    <select>
        <option>Option 1</option>
        <option>Option 2</option>
    </select>
</div>

<div class="form-group">
    <label>Message</label>
    <textarea placeholder="Enter your message"></textarea>
</div>
```

---

## 🎬 Animation Classes

- `.fade-in` - Fades in element
- `.slide-in` - Slides in from top
- `.slide-in-left` - Slides in from left

---

## 📊 Dashboard Example

```html
<div class="grid">
    <div class="stat-card">
        <h4>Total Users</h4>
        <div class="stat-value">2,456</div>
    </div>
    <div class="stat-card" style="border-left-color: #7c3aed;">
        <h4>Revenue</h4>
        <div class="stat-value" style="color: #7c3aed;">$48,950</div>
    </div>
</div>

<div class="card mt-30">
    <div class="card-header">
        <h3>Recent Activity</h3>
    </div>
    <div class="card-body">
        <!-- Chart or content here -->
    </div>
</div>
```

---

## ✅ Checklist

Before launching:
- ✅ Test all buttons work
- ✅ Forms validate properly
- ✅ Tables are readable
- ✅ Mobile looks good
- ✅ Colors are consistent
- ✅ Spacing is even
- ✅ Links work
- ✅ No console errors

---

## 🚨 Common Mistakes to Avoid

❌ Don't use different button styles inconsistently
❌ Don't mix old and new components
❌ Don't skip responsive testing
❌ Don't ignore accessibility
❌ Don't use wrong color for context
❌ Don't forget placeholder text in inputs
❌ Don't overcrowd pages with content
❌ Don't break the grid layout unnecessarily

---

## 📞 Need Help?

Refer to:
1. `UI_DESIGN_GUIDE.md` - Full documentation
2. `example-dashboard.php` - Example page
3. `ui-components.php` - Component showcase
4. CSS variables in `/public/css/style.css`

---

## 🎉 You're All Set!

Start building beautiful interfaces with the new design system!
