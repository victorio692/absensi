# 🎯 QUICK REFERENCE CARD - Landing Page & Persistent Notes

## Akses Cepat

| Item | URL/Command | Status |
|------|-------------|--------|
| Landing Page | `http://localhost:8080/` | ✅ Live |
| Login Page | `http://localhost:8080/login` | ✅ Live |
| Student Dashboard | `http://localhost:8080/student` | ✅ Live |
| Admin Dashboard | `http://localhost:8080/admin` | ✅ Live |
| API Notes | `GET /api/notes` | ✅ Live |

## File Locations

### Landing Page
```
app/Views/landing.php (1100 lines)
├── 8 Sections
├── Responsive CSS
├── JavaScript animations
└── Bootstrap 5.3 + Font Awesome 6.4
```

### Helper Functions
```
app/Helpers/notes_helper.php (8 functions)
├── addNote()
├── addSuccessNote()
├── addErrorNote()
├── addWarningNote()
├── addInfoNote()
├── getUserNotes()
├── getUnreadNotes()
└── markNoteAsRead()
```

### API Controller
```
app/Controllers/Api/NotesController.php (3 endpoints)
├── GET /api/notes
├── POST /api/notes/{id}/read
└── DELETE /api/notes/{id}
```

### Models
```
app/Models/NotesModel.php (9 methods)
├── addNote()
├── getUnreadNotes()
├── getUserNotes()
├── markAsRead()
└── deleteNote()
```

## Helper Functions Cheat Sheet

### Create Notes
```php
// Success note (green, auto-dismiss 5s)
addSuccessNote('Data berhasil disimpan!');

// Error note (red, manual dismiss)
addErrorNote('Terjadi kesalahan pada sistem');

// Warning note (orange, manual dismiss)
addWarningNote('Waktu absensi akan ditutup dalam 5 menit');

// Info note (blue, auto-dismiss 7s)
addInfoNote('Sistem sedang melakukan update');

// Custom note
addNote('success', 'Message', true, 10000); // permanent, 10s timeout
```

### Read Notes
```php
// Get all notes
$notes = getUserNotes();

// Get unread only
$unreadNotes = getUnreadNotes();

// For each note
foreach ($unreadNotes as $note) {
    echo $note['message'];
    echo $note['type']; // success|error|warning|info
}
```

### Update Notes
```php
// Mark as read
markNoteAsRead($note_id);

// Delete
deleteNote($note_id);
```

## API Endpoints Reference

### Get Notes
```bash
curl -X GET http://localhost:8080/api/notes
```

Response:
```json
{
  "status": "success",
  "notes": [
    {
      "id": 1,
      "type": "success",
      "message": "Absensi berhasil",
      "is_read": false,
      "created_at": "2026-02-03 10:30:00"
    }
  ]
}
```

### Mark as Read
```bash
curl -X POST http://localhost:8080/api/notes/1/read
```

Response:
```json
{
  "status": "success",
  "message": "Note marked as read"
}
```

### Delete Note
```bash
curl -X DELETE http://localhost:8080/api/notes/1
```

Response:
```json
{
  "status": "success",
  "message": "Note deleted"
}
```

## Session Management

### User Session Variables
```php
// Set user session
session()->set([
    'user_id' => 1,
    'user_name' => 'John Doe',
    'role' => 'admin'
]);

// Check if logged in
if (session()->has('user_id')) {
    $userId = session()->get('user_id');
}

// Logout
session()->destroy();
```

## Database Quick Queries

### Create Test Notes
```sql
INSERT INTO notes (user_id, type, message, is_permanent) 
VALUES (1, 'success', 'Test note', 0);

INSERT INTO notes (user_id, type, message, is_permanent) 
VALUES (1, 'error', 'Error test', 1);
```

### View Notes
```sql
SELECT * FROM notes WHERE user_id = 1 AND is_read = 0;
```

### Mark as Read
```sql
UPDATE notes SET is_read = 1 WHERE id = 1;
```

### Delete Note
```sql
UPDATE notes SET deleted_at = NOW() WHERE id = 1;
```

## Routes Quick Reference

```php
// Landing page
Route: GET /
Controller: Returns view('landing')

// Login
Route: GET /login
Controller: AuthController::index()

// API Notes
Route: GET /api/notes
Route: POST /api/notes/{id}/read
Route: DELETE /api/notes/{id}
Controller: Api\NotesController::*()

// Protected routes (require session)
Route: GET /student
Route: GET /admin
Filter: auth (checks session()->has('user_id'))
```

## Common Commands

### Start Server
```bash
# Using PHP built-in server
php spark serve

# Using Laragon (if available)
# Just start Laragon, app runs at http://localhost:8080
```

### Database Operations
```bash
# Run migrations
php spark migrate

# Rollback migrations
php spark migrate:rollback

# Refresh migrations
php spark migrate:refresh

# Seed database
php spark db:seed UserSeeder
```

### Cache Management
```bash
# Clear all caches
php spark cache:clear

# Clear specific cache
php spark cache:clear --group views
```

### Testing
```bash
# Run unit tests
php vendor/bin/phpunit

# Run specific test
php vendor/bin/phpunit tests/unit/NoteTest.php
```

## Configuration Files

### .env Variables (Essential)
```
DB_DEFAULT=default
DB_HOST=localhost
DB_NAME=absensi_db
DB_USER=root
DB_PASS=password
DB_PORT=3306

APP_ENVIRONMENT=development
APP_DEBUG=true
```

### app/Config/Database.php
```php
'default' => [
    'hostname' => 'localhost',
    'database' => 'absensi_db',
    'username' => 'root',
    'password' => '',
    'port' => 3306,
]
```

### app/Config/Routes.php
```php
$routes->get('/', static function() { 
    return view('landing'); 
});

$routes->get('/api/notes', 'Api\NotesController::index');
```

## Debugging Tips

### Check Session
```php
echo '<pre>';
print_r(session()->all());
echo '</pre>';
```

### Check if Helper Loaded
```php
if (function_exists('getUnreadNotes')) {
    echo 'Helper loaded!';
} else {
    echo 'Helper NOT loaded!';
}
```

### Debug Notes Query
```php
$model = new \App\Models\NotesModel();
$model->select('*');
$query = $model->getCompiledSelect();
echo $query;
```

### Browser Console Debug
```javascript
// Check if fetch API working
fetch('/api/notes').then(r => r.json()).then(d => console.log(d));

// Check page load
console.log('Page loaded at:', new Date());
```

## Performance Tips

### Optimize Images
```bash
# Use tools like TinyPNG or ImageOptim
# Recommended: Compress images to < 200KB each
```

### Enable Caching
```php
// In config or controller
cache()->save('notes_' . $userId, $notes, 3600);
```

### Database Indexing
```sql
CREATE INDEX idx_user_id ON notes(user_id);
CREATE INDEX idx_created_at ON notes(created_at);
```

## Color Reference

| Type | Color | Hex | Usage |
|------|-------|-----|-------|
| Primary | Purple Blue | #667eea | Buttons, Links |
| Secondary | Dark Purple | #764ba2 | Gradients |
| Success | Green | #48bb78 | Success messages |
| Error | Red | #f56565 | Error messages |
| Warning | Orange | #ed8936 | Warnings |
| Info | Blue | #4299e1 | Info messages |
| Light | Gray | #f7fafc | Backgrounds |
| Dark | Dark Gray | #2d3748 | Text |

## Responsive Breakpoints

```css
/* Mobile First */
< 768px   → Mobile (1 column)
768px     → Tablet (2 columns)
1024px    → Large Tablet (3-4 columns)
1200px    → Desktop (4+ columns)
```

## Important Notes

⚠️ **MUST REMEMBER:**
1. Use `session()->get('user_id')` NOT `auth()->id()`
2. Use `session()->has('user_id')` NOT `auth()->check()`
3. Always call `helper('notes_helper')` before using functions
4. Never output user input without `esc()`
5. Always include `csrf_field()` in forms
6. Use parameterized queries in models
7. Soft delete uses `deleted_at` timestamp
8. Clear cache after major changes
9. Check logs in writable/logs/ for errors
10. Session file location: writable/session/

## Troubleshooting Quick Fixes

| Problem | Solution |
|---------|----------|
| Helper undefined | Clear cache: `php spark cache:clear` |
| Database connection error | Check .env database credentials |
| Page loads slowly | Enable caching, check database indexes |
| CSRF token error | Include `csrf_field()` in form |
| Notes not showing | Check session has user_id |
| Mobile menu not working | Check Bootstrap JS loaded |
| Layout broken | Clear cache, check CSS links |
| 404 on routes | Verify route in Routes.php |

## Quick Testing

### Test Landing Page
```bash
1. Open browser
2. Go to http://localhost:8080/
3. Should see 8 sections
4. Scroll to test animations
5. Click buttons to test navigation
```

### Test Helper
```php
// In controller
addSuccessNote('Test success');
return redirect()->to('/student');
// Then check if note appears at top of page
```

### Test API
```bash
# In terminal
curl -X GET http://localhost:8080/api/notes
# Should return JSON with notes
```

### Test Database
```bash
# In MySQL
SELECT * FROM notes WHERE user_id = 1;
# Should show recently added notes
```

## Links

- 📖 Full Docs: DOKUMENTASI_LANDING_PAGE.md
- 🏗️ Architecture: SYSTEM_ARCHITECTURE.md
- 🧪 Testing: QUICK_START_TESTING.md
- 📋 Checklist: DEVELOPER_CHECKLIST.md
- 📄 README: README_UPDATED.md

---

**Quick Reference v1.0** | Last Updated: 2026-02-03 | Status: ✅ Production Ready
