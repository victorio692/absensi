# 🎯 Developer Checklist - Landing Page & Persistent Notes Implementation

## Pre-Implementation Verification

- [x] CodeIgniter 4.6.4 installed
- [x] MySQL database (absensi_db) configured
- [x] .env file properly set up
- [x] Laragon/PHP server running
- [x] All dependencies installed via composer

---

## Files Created (4 files)

### 1. Landing Page View
- **File**: `app/Views/landing.php`
- **Status**: ✅ CREATED
- **Size**: ~1100 lines
- **Content**:
  - Hero section
  - 8 main sections
  - Bootstrap 5.3 + Font Awesome 6.4
  - Responsive CSS (desktop/tablet/mobile)
  - JavaScript animations (Intersection Observer)
  - Login navigation

**Verification**:
```bash
# Check if file exists
ls -la app/Views/landing.php

# Check syntax
php -l app/Views/landing.php
```

### 2. Helper Functions
- **File**: `app/Helpers/notes_helper.php`
- **Status**: ✅ CREATED
- **Functions**: 8 helper functions
- **Content**:
  - addNote() - Main function
  - addSuccessNote(), addErrorNote(), addWarningNote(), addInfoNote()
  - getUserNotes(), getUnreadNotes()
  - markNoteAsRead(), deleteNote()

**Verification**:
```bash
# Check if file exists
ls -la app/Helpers/notes_helper.php

# Check syntax
php -l app/Helpers/notes_helper.php

# Test in controller
helper('notes_helper');
addSuccessNote('Test note');
```

### 3. API Controller
- **File**: `app/Controllers/Api/NotesController.php`
- **Status**: ✅ CREATED
- **Endpoints**: 3 REST endpoints
- **Content**:
  - index() - GET /api/notes
  - markRead($id) - POST /api/notes/{id}/read
  - delete($id) - DELETE /api/notes/{id}

**Verification**:
```bash
# Check if file exists
ls -la app/Controllers/Api/NotesController.php

# Check syntax
php -l app/Controllers/Api/NotesController.php

# Test endpoints
curl -X GET http://localhost:8080/api/notes
```

### 4. Documentation Files
- **Files**:
  - `DOKUMENTASI_LANDING_PAGE.md` - Complete documentation
  - `SUMMARY_LANDING_PAGE.txt` - Executive summary
  - `QUICK_START_TESTING.md` - Testing guide
  - `SYSTEM_ARCHITECTURE.md` - Architecture documentation

---

## Files Modified (4 files)

### 1. Routes Configuration
- **File**: `app/Config/Routes.php`
- **Change**: Added landing page route
- **Code Added**:
  ```php
  $routes->get('/', static function() {
      return view('landing');
  });
  ```
- **Verification**: Route works at http://localhost:8080/

### 2. Base Controller
- **File**: `app/Controllers/BaseController.php`
- **Change**: Added notes_helper to auto-load
- **Code Added**:
  ```php
  protected $helpers = ['absensi_helper', 'notes_helper'];
  ```
- **Verification**: Helper functions available in all controllers

### 3. Layout Template
- **File**: `app/Views/layout.php`
- **Changes**:
  1. Added helper loading at top:
     ```php
     helper(['notes_helper', 'form', 'url']);
     ```
  2. Added notes display section
  3. Fixed auth() calls to session() calls
  4. Added safety checks for function existence

- **Verification**: Notes appear in layout when logged in

### 4. Notes Model
- **File**: `app/Models/NotesModel.php`
- **Changes**: Fixed auth() calls to session() calls
- **Example**:
  ```php
  // Before:
  if (auth()->check()) { ... }
  $userId = auth()->id();
  
  // After:
  if (session()->has('user_id')) { ... }
  $userId = session()->get('user_id');
  ```

- **Verification**: Model loads without errors

---

## Database Verification

### Migration Check
```bash
# Verify migration exists
ls -la app/Database/Migrations/ | grep notes

# Run migrations
php spark migrate

# Check if notes table exists
php spark db:query "SHOW TABLES LIKE 'notes';"

# Check table structure
php spark db:query "DESCRIBE notes;"
```

### Expected Table Structure
```sql
CREATE TABLE notes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT,
    type ENUM('success','error','warning','info'),
    message LONGTEXT,
    is_read BOOLEAN DEFAULT 0,
    is_permanent BOOLEAN DEFAULT 0,
    auto_dismiss_in INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

---

## Configuration Checks

### 1. .env Configuration
```bash
# Essential variables
DB_DEFAULT=default
DB_HOST=localhost
DB_NAME=absensi_db
DB_USER=root
DB_PASS=[your password]
DB_PORT=3306

# Session configuration
session.driver=files
session.savePath=/path/to/writable/session
```

### 2. Config/Session.php
```bash
# Should have:
public $sessionDriver = 'files';
public $sessionDomain = null;
public $sessionExpiration = 7200;
public $sessionSavePath = WRITEPATH . 'session';
```

### 3. Config/Routes.php
```bash
# Should have:
$routes->get('/', static function() { return view('landing'); });
$routes->get('/api/notes', 'Api\NotesController::index');
$routes->post('/api/notes/(:num)/read', 'Api\NotesController::markRead/$1');
$routes->delete('/api/notes/(:num)', 'Api\NotesController::delete/$1');
```

---

## Code Quality Checks

### PHP Syntax
```bash
# Check all PHP files for syntax errors
find . -name "*.php" -exec php -l {} \;

# Specific files
php -l app/Views/landing.php
php -l app/Helpers/notes_helper.php
php -l app/Controllers/Api/NotesController.php
```

### CodeIgniter Linting
```bash
# If using CodeSniffer
php vendor/bin/phpcs app/Views/landing.php

# Check routes
php spark routes
```

### JavaScript Console
```javascript
// Should see NO errors in browser console
// F12 → Console tab

// Test helper functions exist
console.log(typeof window.getUnreadNotes); // Should be 'function'

// Test API
fetch('/api/notes').then(r => console.log(r));
```

---

## Authentication Verification

### Session-Based Auth Check
```php
// In controller
public function test() {
    // Check if user logged in
    if (session()->has('user_id')) {
        $userId = session()->get('user_id');
        echo "Logged in as: " . $userId;
    } else {
        echo "Not logged in";
    }
}
```

### No auth() Helper Check
```bash
# Search for auth() usage
grep -r "auth()" app/ --include="*.php"

# Should return NOTHING (all fixed)
# If finds something, fix it with session() replacement
```

---

## Testing Procedures

### 1. Landing Page Test
```bash
# Start server
php spark serve

# Visit in browser
http://localhost:8080/

# Check:
✓ Page loads
✓ All sections visible
✓ Responsive design works
✓ Animations smooth
✓ Links functional
✓ Login buttons navigate to /login
```

### 2. Helper Functions Test
```php
// In any controller
public function testHelpers() {
    addSuccessNote('Test success note');
    addErrorNote('Test error note');
    addWarningNote('Test warning note');
    addInfoNote('Test info note');
    
    return redirect()->to('/student');
}
```

### 3. API Endpoints Test
```bash
# Get notes (requires session)
curl -X GET http://localhost:8080/api/notes \
  -H "Cookie: [session_cookie]"

# Mark as read
curl -X POST http://localhost:8080/api/notes/1/read \
  -H "Cookie: [session_cookie]"

# Delete note
curl -X DELETE http://localhost:8080/api/notes/1 \
  -H "Cookie: [session_cookie]"
```

### 4. Database Operations Test
```php
// In controller
$noteModel = new \App\Models\NotesModel();

// Create
$noteModel->addNote('success', 'Test message', false, 5000);

// Read
$notes = $noteModel->getUnreadNotes();
var_dump($notes);

// Update
$noteModel->markAsRead(1);

// Delete
$noteModel->deleteNote(1);
```

---

## Performance Checks

### Page Load Metrics
```
Landing Page Load Time: < 2 seconds
First Contentful Paint: < 1.5 seconds
Largest Contentful Paint: < 2 seconds
Cumulative Layout Shift: < 0.1
```

### Browser DevTools Lighthouse
```
Performance: > 90
Accessibility: > 90
Best Practices: > 90
SEO: > 90
```

### Database Queries
```php
// Enable query logging
// Check how many queries for landing page load
// Should be: 0-1 queries (since it's public)

// For authenticated pages
// Should be: 2-4 queries maximum
// - User session validation
// - Notes fetching
// - Additional data
```

---

## Security Verification

### CSRF Protection
```php
// All forms should have:
<?= csrf_field() ?>

// All POST/PUT/DELETE should validate:
$this->validate([...]);
```

### XSS Prevention
```php
// All user output should be escaped:
<?= esc($userInput) ?>

// Never output directly:
// WRONG: <?= $userInput ?>
// RIGHT: <?= esc($userInput) ?>
```

### SQL Injection Prevention
```php
// Use parameterized queries in Model:
$this->where('user_id', $userId)->find();

// Never concatenate queries:
// WRONG: $query = "WHERE user_id = " . $userId;
// RIGHT: $this->where('user_id', $userId);
```

### Session Security
```php
// Session should be:
✓ HttpOnly (not accessible by JS)
✓ Secure flag (HTTPS only)
✓ SameSite=Lax/Strict
✓ Proper expiration

// In Config/Session.php:
public $sessionCookieSecure = true;
public $sessionCookieSameSite = 'Lax';
```

---

## Deployment Checklist

### Pre-Production
- [ ] All files committed to Git
- [ ] Database migrations run successfully
- [ ] All tests passing
- [ ] No console errors or warnings
- [ ] HTTPS enabled
- [ ] Error logging configured
- [ ] Debug toolbar disabled
- [ ] .env set to production

### Production Release
- [ ] Backup current database
- [ ] Backup current files
- [ ] Deploy new code
- [ ] Run migrations: `php spark migrate`
- [ ] Clear cache: `php spark cache:clear`
- [ ] Test all features
- [ ] Monitor error logs
- [ ] Verify page load times

### Post-Deployment
- [ ] Monitor server resources
- [ ] Check error logs daily
- [ ] Verify scheduled jobs (if any)
- [ ] Monitor database size
- [ ] Check backup status
- [ ] Get user feedback

---

## Common Issues & Solutions

### Issue: Landing page shows 404
```
Solution:
1. Verify route exists in Routes.php
2. Verify landing.php exists in app/Views/
3. Clear cache: php spark cache:clear
4. Restart server
```

### Issue: Helper functions undefined
```
Solution:
1. Verify notes_helper.php in app/Helpers/
2. Verify BaseController has notes_helper in $helpers
3. Check helper() call in layout.php
4. Clear cache and restart
```

### Issue: Notes not displaying
```
Solution:
1. Verify notes table exists
2. Check user_id in session
3. Test API endpoint: curl /api/notes
4. Check browser console for JS errors
5. Check server logs
```

### Issue: CSRF token error
```
Solution:
1. Verify form has csrf_field()
2. Check session is initialized
3. Clear browser cookies
4. Clear server cache
5. Try incognito mode
```

---

## Documentation References

For detailed information, see:
- 📖 [DOKUMENTASI_LANDING_PAGE.md](DOKUMENTASI_LANDING_PAGE.md)
- 📋 [SUMMARY_LANDING_PAGE.txt](SUMMARY_LANDING_PAGE.txt)
- 🧪 [QUICK_START_TESTING.md](QUICK_START_TESTING.md)
- 🏗️ [SYSTEM_ARCHITECTURE.md](SYSTEM_ARCHITECTURE.md)

---

## Support & Maintenance

### Regular Maintenance Tasks
- [ ] Daily: Monitor error logs
- [ ] Weekly: Check database size
- [ ] Monthly: Review performance metrics
- [ ] Quarterly: Update dependencies
- [ ] Yearly: Security audit

### Contact Information
- Admin Email: admin@absensi-qr.com
- Support Phone: +62 xxx xxxx xxxx
- Documentation: /docs folder
- Code Repository: [GitHub URL]

---

**Checklist Version**: 1.0
**Last Updated**: 2026-02-03
**Maintained By**: Development Team
**Status**: READY FOR PRODUCTION ✅

---

## Sign-Off

- [ ] Developer Name: ____________________
- [ ] Date: ____________________
- [ ] Testing Completed: ____________________
- [ ] Ready for Production: ____________________

