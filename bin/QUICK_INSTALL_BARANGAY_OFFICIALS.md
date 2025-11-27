# 🚀 Barangay Officials Org Chart - Quick Installation

## ✅ Installation Checklist

Follow these steps in order:

### **Step 1: Install Database** ⚡
```bash
1. Open phpMyAdmin in your browser
2. Select your database (the one your system uses)
3. Click "SQL" tab
4. Open file: database/migrations/barangay_officials_setup.sql
5. Copy all SQL code
6. Paste into SQL tab
7. Click "Go" button
8. Verify "12 rows inserted" message
```

**Expected Result:** ✅ Table `barangay_officials` created with 12 positions

---

### **Step 2: Create Upload Directory** 📁
```bash
# Navigate to your project root
cd c:\xampp\htdocs\FINAL_PROJECT_ELECTIVE1

# Create officials upload folder
mkdir assets\uploads\officials

# Or create manually via Windows Explorer:
# - Go to assets/uploads/
# - Create new folder named "officials"
```

**Expected Result:** ✅ Folder `assets/uploads/officials/` exists

---

### **Step 3: Add Default Placeholder Images** 🖼️
Option A - Quick Method (Use Placeholder Generator):
```
1. Open browser
2. Visit: https://via.placeholder.com/400x400/4e73df/ffffff?text=Official
3. Right-click → Save Image As...
4. Save to: assets/img/default_official.png
5. Repeat for other positions (optional)
```

Option B - Use Avatar Generator:
```
1. Visit: https://ui-avatars.com/
2. Enter name: "Chairman"
3. Size: 400
4. Download PNG
5. Rename to: default_chairman.png
6. Save to: assets/img/
```

**Required Images (at minimum):**
- `default_official.png` (fallback - REQUIRED)

**Optional Images (for better appearance):**
- `default_chairman.png`
- `default_secretary.png`
- `default_treasurer.png`
- `default_kagawad.png`
- `default_sk.png`
- `default_tanod.png`

**Expected Result:** ✅ At least `default_official.png` exists in `assets/img/`

---

### **Step 4: Verify File Permissions (Linux/Mac only)** 🔐
```bash
# Skip this step if on Windows
chmod 755 App/Controller/BarangayOfficialController.php
chmod 755 App/Model/BarangayOfficial.php
chmod 755 App/View/barangay_officials.php
chmod 755 assets/uploads/officials
```

**Expected Result:** ✅ Files are executable (Linux/Mac only)

---

### **Step 5: Test the Module** 🧪
```
1. Start XAMPP (Apache and MySQL)
2. Open browser
3. Go to: http://localhost/FINAL_PROJECT_ELECTIVE1/App/View/index.php
4. Login as Admin
5. Navigate: Sidebar → Maintenance → Barangay Official Org Chart
6. Verify all 12 positions display
```

**Expected Result:** ✅ Page loads with 12 official cards in hierarchy

---

### **Step 6: Test Editing Features** ✏️
```
1. Click "Edit Name" on any official card
2. Change name to "Test Name"
3. Click "Save Changes"
4. Verify page refreshes with new name

5. Click "Photo" on any official card
6. Choose an image file (JPG/PNG, under 5MB)
7. Click "Upload Photo"
8. Verify page refreshes with new photo
```

**Expected Result:** ✅ Name and photo update successfully

---

## 📊 Verification Checklist

After installation, verify these items:

- [ ] Database table `barangay_officials` exists
- [ ] 12 positions are in database
- [ ] Page loads without errors
- [ ] All 4 hierarchy levels display
- [ ] Statistics cards show correct counts (12 total, 7 kagawads, 2 executives)
- [ ] "Edit Name" button opens modal
- [ ] "Photo" button opens modal
- [ ] Name editing works and saves
- [ ] Photo upload works and displays
- [ ] Images store in `assets/uploads/officials/`
- [ ] Navigation link appears in Maintenance section

---

## 🎨 Visual Verification

**Hierarchy Levels Should Display:**

1. **Level 1 (Blue):** Barangay Chairman - centered, large card
2. **Level 2 (Green):** Secretary & Treasurer - 2 cards side by side
3. **Level 3 (Cyan):** 7 Kagawads - grid layout
4. **Level 4 (Yellow):** SK Chairman & Tanod - 2 cards side by side

**Cards Should Have:**
- Square profile image at top
- Position title (gray, uppercase)
- Official name (large, bold)
- Two buttons: "Edit Name" (blue) and "Photo" (green)
- Hover effect (lift and shadow)
- Color-coded border matching level

---

## 🐛 Common Issues & Solutions

### **Issue: Page shows 404 Not Found**
**Solution:** 
- Verify file path: `App/View/barangay_officials.php`
- Check navigation link in sidebar
- Ensure you're logged in as Admin

---

### **Issue: Database table doesn't exist**
**Solution:**
- Re-run SQL migration file
- Check database is selected in phpMyAdmin
- Verify no SQL errors in console

---

### **Issue: Images don't display**
**Solution:**
- Check `assets/img/default_official.png` exists
- Verify upload folder: `assets/uploads/officials/`
- Check file permissions (Linux/Mac)
- Open browser console for 404 errors

---

### **Issue: Upload fails**
**Solution:**
- Check PHP settings in `php.ini`:
  - `upload_max_filesize = 5M`
  - `post_max_size = 6M`
  - `file_uploads = On`
- Restart Apache after changing php.ini
- Verify folder permissions

---

### **Issue: Can't access page (403 Forbidden)**
**Solution:**
- Login as Admin (roleId must be 1)
- Check RBAC middleware is working
- Verify session is active

---

### **Issue: Modals don't open**
**Solution:**
- Check Bootstrap JS is loaded
- Open browser console for errors
- Verify jQuery is loaded (if required)
- Clear browser cache

---

## 🎯 Quick Test Commands

### **Test Database Connection:**
```sql
-- Run in phpMyAdmin SQL tab
SELECT COUNT(*) as total FROM barangay_officials;
-- Expected result: 12
```

### **Test API Endpoint:**
```
Open browser and visit:
http://localhost/FINAL_PROJECT_ELECTIVE1/App/Controller/BarangayOfficialController.php?action=getAll

Expected: JSON response with 12 officials
```

### **Test Upload Directory:**
```bash
# Windows
dir assets\uploads\officials

# Linux/Mac
ls -la assets/uploads/officials
```

---

## 📞 Need Help?

**Check These First:**
1. XAMPP Apache and MySQL are running
2. Database exists and is selected
3. Logged in as Admin user
4. Browser console for JavaScript errors
5. PHP error logs for backend errors

**Documentation:** `bin/BARANGAY_OFFICIALS_ORG_CHART_DOCUMENTATION.md`

---

## ✨ Success Indicators

You'll know it's working when:
- ✅ Page loads with all 12 positions
- ✅ Cards display in 4 colored hierarchy levels
- ✅ Clicking "Edit Name" opens modal
- ✅ Name changes save and refresh page
- ✅ Photo upload works and displays immediately
- ✅ Statistics cards show correct counts
- ✅ Hover effects work on cards

---

**Installation Time:** ~5-10 minutes  
**Difficulty:** Easy  
**Last Updated:** November 26, 2025
