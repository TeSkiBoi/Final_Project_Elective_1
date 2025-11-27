# 🏛️ Barangay Officials Organizational Chart Module

## Overview
A comprehensive module for managing and displaying the official organizational structure of the barangay. Features a visually appealing hierarchical layout with real-time editing capabilities for official names and photos.

---

## ✅ Files Created

### **1. Database Migration**
**File:** `database/migrations/barangay_officials_setup.sql`

**Table:** `barangay_officials`
- `id` (INT, PK, AUTO_INCREMENT)
- `position_title` (VARCHAR 100, UNIQUE) - Position name (not editable)
- `full_name` (VARCHAR 255) - Official's name (editable)
- `image_path` (VARCHAR 255) - Path to official's photo
- `display_order` (INT) - Hierarchy order
- `is_active` (ENUM: Yes/No) - Soft delete flag
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

**Default Positions Inserted:**
1. Barangay Chairman (display_order: 1)
2. Barangay Secretary (display_order: 2)
3. Barangay Treasurer (display_order: 3)
4. Barangay Kagawad 1-7 (display_order: 4-10)
5. SK Chairman (display_order: 11)
6. Barangay Tanod (display_order: 12)

---

### **2. Model Layer**
**File:** `App/Model/BarangayOfficial.php`

**Methods:**
- `getAll()` - Fetch all active officials ordered by display_order
- `getById($id)` - Get single official by ID
- `getByPosition($position_title)` - Get official by position
- `update($id, $data)` - Update full record
- `updateName($id, $full_name)` - Update only name
- `updateImage($id, $image_path)` - Update only photo
- `updateOrder($id, $display_order)` - Update display order (drag & drop)
- `create($data)` - Create new position (future expansion)
- `delete($id)` - Soft delete (set is_active='No')
- `permanentDelete($id)` - Hard delete from database
- `getByHierarchy()` - Group officials by hierarchy level
- `countOfficials()` - Count total active officials
- `positionExists($position_title, $excludeId)` - Check duplicate positions

---

### **3. Controller Layer**
**File:** `App/Controller/BarangayOfficialController.php`

**API Endpoints:**
- `?action=getAll` (GET) - Fetch all officials
- `?action=getById&id={id}` (GET) - Get single official
- `?action=updateName` (POST) - Update official name
  - Required: `id`, `full_name`
  - Validates: Name format (letters, spaces, punctuation only)
- `?action=updateImage` (POST) - Upload official photo
  - Required: `id`, `image` (file upload)
  - Validates: File type (JPG, PNG, GIF), size (max 5MB)
  - Auto-deletes old image
- `?action=update` (POST) - Update full record
- `?action=create` (POST) - Create new position
- `?action=delete` (POST) - Soft delete official
- `?action=updateOrder` (POST) - Update display order

**Features:**
- File upload handling with validation
- Image path management
- Old image cleanup
- JSON responses
- Exception handling

---

### **4. View Layer**
**File:** `App/View/barangay_officials.php`

**Layout Structure:**

#### **1. Introduction Card**
- Module description
- Key features list
- Standard positions list

#### **2. Statistics Cards (3 cards)**
- Total Officials (Blue)
- Barangay Kagawads (Green)
- Executive Officers (Cyan)

#### **3. Organizational Chart**
Hierarchical display with 4 levels:

**Level 1: Barangay Leadership** (Blue)
- Barangay Chairman
- Centered, larger card

**Level 2: Executive Officers** (Green)
- Barangay Secretary
- Barangay Treasurer
- Side-by-side cards

**Level 3: Barangay Council** (Cyan)
- 7 Barangay Kagawads
- Grid layout (3-4 per row)

**Level 4: Youth & Security** (Yellow)
- SK Chairman
- Barangay Tanod
- Side-by-side cards

#### **Official Card Components:**
- Profile photo (square, full-width)
- Position title (uppercase, gray)
- Full name (bold, large)
- "Edit Name" button (blue)
- "Photo" button (green)

**Card Features:**
- Hover effects (lift + shadow)
- Color-coded borders by level
- Responsive grid layout
- Image overlay badge
- Uniform heights

#### **5. Modals**

**Edit Name Modal:**
- Position title (readonly)
- Full name input (required, max 255)
- Save/Cancel buttons
- Form validation

**Edit Image Modal:**
- Position title (readonly)
- File upload input (JPG/PNG/GIF, max 5MB)
- Current photo preview
- Real-time preview on file select
- Upload/Cancel buttons

---

### **5. Navigation Integration**
**File:** `App/View/template/sidebar_navigation.php`

**Location:** Maintenance → Barangay Official Org Chart
- Icon: `fa-sitemap`
- Access: Admin only (roleId == 1)
- Positioned after User and Role

---

## 🎨 Design Features

### **Visual Hierarchy**
1. **Chairman Level** - Blue theme, centered, prominent
2. **Executive Level** - Green theme, 2 columns
3. **Kagawad Level** - Cyan theme, grid layout
4. **Youth/Security Level** - Yellow theme, 2 columns

### **Card Styling**
```css
- Border radius: 12px
- Hover effect: translateY(-5px) + shadow
- Image: Square (1:1 ratio), gradient background
- Typography: Responsive, bold names
- Actions: Full-width button group
- Colors: Level-specific border colors
```

### **Responsive Design**
- Desktop (lg): 3-4 cards per row for kagawads
- Tablet (md): 2 cards per row
- Mobile (sm): 1 card per row
- Images maintain aspect ratio

### **Interactive Elements**
- Hover animations on cards
- Modal transitions
- File upload preview
- SweetAlert2 notifications
- Loading indicators

---

## 🚀 Installation Steps

### **1. Install Database**
```bash
# Open phpMyAdmin
# Select your database
# Go to SQL tab
# Run the file:
database/migrations/barangay_officials_setup.sql
```

### **2. Create Upload Directory**
```bash
mkdir -p assets/uploads/officials
chmod 755 assets/uploads/officials
```

### **3. Add Default Images**
Place default placeholder images in `assets/img/`:
- `default_chairman.png`
- `default_secretary.png`
- `default_treasurer.png`
- `default_kagawad.png`
- `default_sk.png`
- `default_tanod.png`
- `default_official.png` (fallback)

Recommended size: 400x400px, PNG format

### **4. Set File Permissions**
```bash
chmod 755 App/Controller/BarangayOfficialController.php
chmod 755 App/Model/BarangayOfficial.php
chmod 755 App/View/barangay_officials.php
```

### **5. Access the Module**
- Login as Admin
- Navigate: Sidebar → Maintenance → Barangay Official Org Chart
- URL: `App/View/barangay_officials.php`

---

## 📖 User Guide

### **Viewing the Org Chart**
1. Navigate to "Barangay Official Org Chart" in sidebar
2. View all officials organized by hierarchy
3. See statistics at the top (Total, Kagawads, Executives)

### **Editing Official Names**
1. Click "Edit Name" button on any official card
2. Enter new name in the modal
3. Click "Save Changes"
4. Page refreshes with updated name

### **Uploading Official Photos**
1. Click "Photo" button on any official card
2. Click "Choose File" in the modal
3. Select image (JPG/PNG/GIF, max 5MB)
4. Preview appears immediately
5. Click "Upload Photo"
6. Page refreshes with new photo

**Image Requirements:**
- Format: JPG, PNG, or GIF
- Max size: 5MB
- Recommended: Square images (400x400px)
- Photos stored in: `assets/uploads/officials/`

### **Best Practices**
- Use professional, high-quality photos
- Maintain consistent photo styles (background, lighting)
- Use proper names with titles (e.g., "Hon. Juan Dela Cruz")
- Update photos when officials change

---

## 🔐 Security Features

### **Access Control**
- Only Admin role can access (roleId == 1)
- Protected by `AuthMiddleware::authorize(['Admin'])`
- Non-admins redirected or denied

### **Input Validation**
- **Name validation:** Letters, spaces, dots, hyphens, apostrophes only
- **File validation:** Type (JPG/PNG/GIF), size (5MB max)
- **ID validation:** Numeric, exists in database
- **SQL injection protection:** Prepared statements

### **File Security**
- Upload directory outside web root recommended
- Unique filenames prevent overwrites
- Old images automatically deleted
- File type verification
- Size limits enforced

---

## 📊 Database Schema Details

### **Indexes**
```sql
INDEX idx_position_title (position_title)
INDEX idx_display_order (display_order)
INDEX idx_is_active (is_active)
UNIQUE KEY unique_position (position_title)
```

### **Constraints**
- `position_title` must be unique
- `is_active` defaults to 'Yes'
- `display_order` determines hierarchy

### **Sample Data**
12 positions inserted by default with placeholder data:
- All positions have sample names
- All use default placeholder images
- Display order: 1-12 (hierarchical)

---

## 🎯 Features & Functionality

### **Core Features**
✅ Visual organizational hierarchy display
✅ Real-time name editing with validation
✅ Photo upload with preview
✅ Responsive card-based layout
✅ Color-coded hierarchy levels
✅ Hover effects and animations
✅ Modal-based editing
✅ SweetAlert2 notifications
✅ Automatic image cleanup
✅ Statistics dashboard

### **Technical Features**
✅ MVC architecture
✅ RESTful API endpoints
✅ JSON responses
✅ File upload handling
✅ Prepared statements (SQL injection protection)
✅ Exception handling
✅ Form validation
✅ Bootstrap 5 modals
✅ Chart.js integration-ready

### **Admin Features**
✅ Edit any official's name
✅ Upload/change official photos
✅ View complete organizational structure
✅ Statistics overview
✅ Access restricted to admin only

---

## 🔄 Future Enhancements (Optional)

### **Potential Additions:**
1. **Drag & Drop Reordering**
   - Implement Sortable.js
   - Update `display_order` via `updateOrder` endpoint
   - Visual feedback during drag

2. **Additional Fields**
   - Contact number
   - Email address
   - Term start/end dates
   - Committee assignments

3. **Print/Export Features**
   - PDF export of org chart
   - Print-friendly layout
   - Contact directory export

4. **Public Display**
   - Read-only public page
   - No edit buttons for non-admin
   - Embedded on website

5. **History Tracking**
   - Log official changes
   - Previous officials archive
   - Term history

6. **Advanced Layout Options**
   - Tree diagram view
   - List view
   - Grid view toggle

---

## 🐛 Troubleshooting

### **Images Not Displaying**
- Check upload directory exists: `assets/uploads/officials/`
- Verify file permissions: `chmod 755`
- Ensure default images in `assets/img/`
- Check image paths in database

### **Upload Fails**
- Check PHP upload settings:
  - `upload_max_filesize = 5M`
  - `post_max_size = 6M`
  - `file_uploads = On`
- Verify directory permissions
- Check file size and type

### **Can't Edit Names**
- Verify logged in as Admin
- Check RBAC middleware
- Inspect browser console for errors
- Verify API endpoint accessible

### **Modal Doesn't Open**
- Ensure Bootstrap JS loaded
- Check for JavaScript errors
- Verify modal IDs match

---

## 📁 File Structure
```
FINAL_PROJECT_ELECTIVE1/
├── App/
│   ├── Controller/
│   │   └── BarangayOfficialController.php
│   ├── Model/
│   │   └── BarangayOfficial.php
│   └── View/
│       ├── barangay_officials.php
│       └── template/
│           └── sidebar_navigation.php (updated)
├── assets/
│   ├── img/
│   │   ├── default_chairman.png
│   │   ├── default_secretary.png
│   │   ├── default_treasurer.png
│   │   ├── default_kagawad.png
│   │   ├── default_sk.png
│   │   ├── default_tanod.png
│   │   └── default_official.png
│   └── uploads/
│       └── officials/ (created automatically)
└── database/
    └── migrations/
        └── barangay_officials_setup.sql
```

---

## 🎓 Developer Notes

### **Code Standards**
- PSR-12 coding standards
- Prepared statements for all queries
- Exception handling throughout
- Consistent naming conventions
- Comprehensive comments

### **API Response Format**
```json
{
  "success": true/false,
  "message": "Descriptive message",
  "data": {} // Optional
}
```

### **Extending the Module**
To add new positions:
1. Insert into database with unique `position_title`
2. Set appropriate `display_order`
3. Refresh page - automatic display

To modify hierarchy grouping:
- Edit `getByHierarchy()` method in Model
- Adjust conditions for level assignment

---

## ✨ Conclusion

The **Barangay Officials Organizational Chart** module is now complete and ready for use. It provides:
- ✅ Professional visual hierarchy
- ✅ Easy name and photo management
- ✅ Responsive, modern design
- ✅ Secure admin-only access
- ✅ Full CRUD functionality
- ✅ Comprehensive validation

The module integrates seamlessly with your existing Barangay Management System and follows all established patterns and conventions.

---

**Last Updated:** November 26, 2025  
**Version:** 1.0  
**Status:** ✅ Complete and Ready for Production
