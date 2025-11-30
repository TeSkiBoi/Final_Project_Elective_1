# Database Migration Instructions

## Overview
This migration adds support for selecting household head from residents instead of entering it as text.

## What Changed

### Database Changes:
- Added `household_head_id` column (VARCHAR(20)) to `households` table
- Made `full_name` column nullable (for backwards compatibility)
- Added foreign key constraint linking `household_head_id` to `residents.resident_id`
- Added index on `household_head_id` for performance

### Application Changes:
- **Create Household**: No longer requires household head during creation
- **Edit Household**: Shows dropdown to select household head from current members
- **Table Display**: Shows household head name or "Not Set" if no head selected

## How to Apply Migration

### Option 1: Using MySQL Command Line
```bash
mysql -u root -p barangay_biga_db < database/migrations/add_household_head_id.sql
```

### Option 2: Using phpMyAdmin
1. Open phpMyAdmin in your browser (http://localhost/phpmyadmin)
2. Select the `barangay_biga_db` database from the left sidebar
3. Click the "SQL" tab at the top
4. Copy the entire contents of `database/migrations/add_household_head_id.sql`
5. Paste into the SQL query box
6. Click "Go" to execute

### Option 3: Manual Execution
Run these SQL commands one by one in your MySQL client:

```sql
-- Step 1: Add household_head_id column
ALTER TABLE `households` 
ADD COLUMN `household_head_id` VARCHAR(20) NULL AFTER `family_no`;

-- Step 2: Make full_name nullable
ALTER TABLE `households` 
MODIFY COLUMN `full_name` VARCHAR(150) NULL;

-- Step 3: Add foreign key constraint
ALTER TABLE `households`
ADD CONSTRAINT `fk_household_head`
FOREIGN KEY (`household_head_id`) REFERENCES `residents`(`resident_id`)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- Step 4: Add index
CREATE INDEX `idx_household_head` ON `households`(`household_head_id`);
```

## Verification Steps

After running the migration, verify it worked:

```sql
-- Check if columns were added
DESCRIBE households;

-- Check if foreign key was created
SHOW CREATE TABLE households;

-- Check if index exists
SHOW INDEX FROM households;
```

You should see:
- `household_head_id` column with type VARCHAR(20) and NULL allowed
- `full_name` column with NULL allowed
- Foreign key `fk_household_head` pointing to `residents(resident_id)`
- Index `idx_household_head` on `household_head_id`

## Testing the New Workflow

1. **Create a new household:**
   - Go to Households page
   - Click "Add New Household"
   - Fill in Family No, Address, and Income
   - Submit (no household head required)

2. **Edit household to add members:**
   - Click "Edit" on the newly created household
   - Click "Add Member" to add residents
   - Fill in member details and save

3. **Select household head:**
   - Edit the household again
   - You should see a dropdown "Household Head" populated with the residents you added
   - Select one as the household head
   - Save

4. **Verify in table:**
   - The household table should now show the selected resident's name in the "Household Head" column
   - If no head is selected, it shows "Not Set"

## Important Notes

- Existing households will have `household_head_id` as NULL until you edit them and select a head
- The `full_name` column is kept for backwards compatibility but is no longer used by the application
- If a resident who is a household head is deleted, the `household_head_id` is automatically set to NULL
- You must add members before you can select a household head
- The household head must be an existing member of that household

## Rollback (if needed)

If you need to undo this migration:

```sql
-- Remove foreign key
ALTER TABLE households DROP FOREIGN KEY fk_household_head;

-- Remove column
ALTER TABLE households DROP COLUMN household_head_id;

-- Make full_name required again (optional)
ALTER TABLE households MODIFY COLUMN full_name VARCHAR(150) NOT NULL;

-- Remove index
DROP INDEX idx_household_head ON households;
```

Then restore the old code files from your backup or version control.

## Troubleshooting

**Error: Cannot add foreign key constraint**
- Make sure the `residents` table exists
- Ensure `resident_id` in `residents` table is VARCHAR(20)
- Check that there are no existing values in `household_head_id` that don't exist in `residents.resident_id`

**Error: Duplicate column name 'household_head_id'**
- The column already exists. Skip Step 1 or drop it first: `ALTER TABLE households DROP COLUMN household_head_id;`

**Dropdown is empty when editing**
- Make sure you've added members to the household first
- Check that the `getMembers` API endpoint is working
- Check browser console for JavaScript errors
