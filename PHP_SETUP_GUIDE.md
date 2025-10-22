# PHP Setup Guide - **QUICK FIX FIRST**

## 🚨 **Immediate Solution (Try This First)**

If you're getting database connection errors:

1. **Open XAMPP Control Panel** (search "xampp" in Windows search)
2. **Start MySQL**: Click "Start" next to MySQL → wait for green indicator
3. **Start Apache**: Click "Start" next to Apache → wait for green indicator
4. **Test Fix**: Visit `http://localhost:8000/fix_database.php`
5. **Check Status**: Visit `http://localhost:8000/html/system_check.php`

**If that doesn't work, continue with the full setup below.**

---

## Problem
The dashboard shows errors because PHP is not configured to work with the current web server setup.

## Current Status
✅ **Dashboard Interface**: Working perfectly with JavaScript fallback
✅ **Charts & Visualizations**: Fully functional with mock data
✅ **Responsive Design**: Complete UI with all features
❌ **Live Database**: Requires PHP/MySQL setup

## Solution Options

### Option 1: XAMPP (Recommended - Complete Solution)

1. **Download and Install XAMPP**
   - Go to https://www.apachefriends.org/
   - Download XAMPP for Windows
   - Run the installer and follow the setup wizard

2. **Start XAMPP Services**
   - Open XAMPP Control Panel
   - Click "Start" for Apache (web server)
   - Click "Start" for MySQL (database)
   - Both should show green indicators

3. **Configure PHP**
   - In XAMPP Control Panel, click "Config" next to Apache
   - Select "PHP (php.ini)"
   - Ensure these lines are uncommented (remove #):
     ```
     extension=php_mysqli
     extension=php_pdo_mysql
     ```

4. **Place Files in Correct Directory**
   - Copy the entire `reports` folder to:
     `C:\xampp\htdocs\`
   - Or create a new folder like `C:\xampp\htdocs\school-reports\`

5. **Access the Application**
   - Open browser and go to: `http://localhost/reports/html/teacher_dashboard.php`
   - Or if using a subfolder: `http://localhost/school-reports/html/teacher_dashboard.php`

### Option 2: WAMP Server (Alternative)

1. **Download WAMP**
   - Go to https://www.wampserver.com/
   - Download and install WAMP

2. **Start Services**
   - WAMP should start automatically
   - Look for green W icon in system tray

3. **Place Files**
   - Copy to `C:\wamp64\www\reports\`
   - Access via `http://localhost/reports/html/teacher_dashboard.php`

### Option 3: Manual PHP Installation

1. **Install PHP**
   - Download from https://windows.php.net/
   - Add PHP to system PATH
   - Install MySQL separately

2. **Configure Web Server**
   - Use Apache or Nginx
   - Configure PHP as a module

## Testing PHP Setup

### Quick Tests

1. **PHP Info Test**
   - Create `C:\xampp\htdocs\phpinfo.php` with content:
     ```php
     <?php phpinfo(); ?>
     ```
   - Visit `http://localhost/phpinfo.php`
   - Should show PHP version and configuration

2. **Database Test**
   - Visit `http://localhost/phpmyadmin`
   - Login with username: `root`, password: (empty)
   - Should show database management interface

3. **Dashboard Test**
   - Visit `http://localhost/reports/html/system_check.php`
   - Should show all green checkmarks

## Current Dashboard Features (Working Now)

Even without PHP, the dashboard provides:

### ✅ **Visual Interface**
- Complete responsive design
- Professional styling with TailwindCSS
- Interactive navigation menu
- Mobile-friendly layout

### ✅ **Charts & Analytics**
- Performance trend charts
- Grade distribution doughnut charts
- Real-time data updates (simulated)
- Interactive hover effects

### ✅ **Sample Data Display**
- Mock student statistics
- Recent activity feed
- Grade breakdowns
- Live updating numbers

### ✅ **User Experience**
- Loading states and animations
- Error handling with helpful messages
- Setup guidance and links
- Responsive data simulation

## Benefits of PHP Setup

Once PHP/MySQL is configured, you'll get:

### 🚀 **Live Database Integration**
- Real student data from database
- Actual statistics and calculations
- Persistent data storage
- Multi-user support

### 🔄 **Dynamic Updates**
- Real-time data from database
- Automatic refresh every 30 seconds
- Live chart updates
- Recent activity from actual entries

### 💾 **Data Management**
- Student mark entry and storage
- Report generation and export
- Historical data tracking
- Backup and restore capabilities

## Getting Started (Current Status)

**Right now, you can:**

1. **View Dashboard**: `http://localhost:8000/html/teacher_dashboard.php`
   - See complete interface with sample data
   - Test all navigation and features
   - Experience the full user interface

2. **Test Features**: `http://localhost:8000/html/dashboard_demo.php`
   - Pure HTML/CSS/JS version
   - No PHP dependencies
   - Perfect for demonstration

3. **System Check**: `http://localhost:8000/html/system_check.php`
   - Diagnose PHP and database status
   - Get setup guidance

## Next Steps

1. **Install XAMPP** (easiest option)
2. **Copy files** to `C:\xampp\htdocs\reports\`
3. **Start services** in XAMPP Control Panel
4. **Run setup**: Visit `http://localhost/reports/html/setup.php`
5. **Enjoy live data!** 🎉

The dashboard is fully functional as a demo right now. With PHP setup, it becomes a complete school management system!
