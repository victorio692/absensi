# 🚀 Quick Start Guide - Testing Landing Page

## Langkah-Langkah Cepat Testing

### 1. Pastikan Server Berjalan
```bash
# Terminal 1: Jalankan Laragon/PHP Server
php -S localhost:8080 -t public
# atau
spark serve
```

### 2. Test Landing Page
```
Buka browser: http://localhost:8080/
```

**Apa yang akan kamu lihat:**
- ✅ Hero section dengan gradient ungu
- ✅ 8 sections penjelasan sistem
- ✅ Responsive design (coba resize browser)
- ✅ Smooth scroll animations
- ✅ Login buttons yang berfungsi

### 3. Test Navigation
```
Klik links di navbar:
- "Fitur" → Scroll ke features section
- "Cara Kerja" → Scroll ke how-it-works section  
- "Keunggulan" → Scroll ke advantages section
- "Masuk" → Navigate ke /login page
```

### 4. Test Responsive Design
```
Press F12 → Toggle Device Toolbar (Ctrl+Shift+M)
- Test di Mobile (iPhone)
- Test di Tablet (iPad)
- Test di Desktop (1920x1080)

Pastikan:
✓ Hamburger menu muncul di mobile
✓ Text readable di semua ukuran
✓ Buttons clickable di mobile
✓ No horizontal scroll
```

### 5. Test Login Integration
```
Click "Login Siswa" button
→ Should navigate to http://localhost:8080/login

Test login dengan credentials:
Username: [sesuai database]
Password: [sesuai database]

After login:
→ Should redirect ke /student dashboard
→ Persistent notes should appear di top
```

### 6. Test Persistent Notes
```
Di controller atau helper, tambahkan:

addSuccessNote('Selamat datang kembali!');
addWarningNote('Ada 2 kehadiran yang belum dicatat');

Maka akan terlihat di atas layout sebagai:
✓ Notes dengan auto-dismiss atau persist
✓ Tombol "Baca" untuk mark as read
✓ Tombol "Hapus" untuk delete
✓ Animasi smooth saat muncul/hilang
```

### 7. Test API Endpoints
```bash
# Get unread notes
curl -X GET http://localhost:8080/api/notes

# Mark note as read
curl -X POST http://localhost:8080/api/notes/1/read

# Delete note  
curl -X DELETE http://localhost:8080/api/notes/1
```

---

## 📱 Mobile Testing Checklist

### Viewport 375x667 (Mobile)
- [ ] Logo visible dan clear
- [ ] Hamburger menu visible
- [ ] Hero buttons full width
- [ ] Cards stacked properly (1 column)
- [ ] No horizontal scrollbar
- [ ] Touch-friendly buttons (min 44px height)

### Viewport 768x1024 (Tablet)
- [ ] 2-column layout for cards
- [ ] Navbar properly sized
- [ ] Touch interactions working

### Viewport 1920x1080 (Desktop)
- [ ] 3-4 column layout for cards
- [ ] Full navbar with all links
- [ ] Smooth animations visible
- [ ] Floating background shapes animating

---

## 🎨 Visual Checklist

- [ ] Colors correct:
  - Primary Purple: #667eea
  - Secondary Purple: #764ba2
  - White backgrounds on cards
  
- [ ] Typography:
  - Headings bold and large
  - Body text readable (14-16px)
  - Good contrast ratio (WCAG AA)

- [ ] Animations:
  - Fade-in elements saat scroll
  - Hover effects pada cards
  - Smooth transitions (0.3s)
  - Floating shapes di hero

- [ ] Spacing:
  - Consistent padding/margins
  - Good whitespace
  - 80px padding per section

---

## 🔧 Browser Console Check

Open DevTools Console (F12) and verify:

```javascript
// Should not see any errors
console.log('✓ No JavaScript errors');

// Check if helpers loaded
console.log(typeof window.getUnreadNotes); // Should be 'function'

// Check fetch API working
fetch('/api/notes').then(r => console.log('✓ API working'));
```

---

## 📊 Performance Testing

### With DevTools Lighthouse:
1. Press F12 → Lighthouse tab
2. Run Audit
3. Check scores:
   - Performance: > 90
   - Accessibility: > 90
   - Best Practices: > 90
   - SEO: > 90

### Load Time:
```
Ideal load time: < 2 seconds
Document ready: < 1.5 seconds
```

---

## ⚠️ Common Issues & Fixes

### Issue: Landing page not loading
```
Solution:
1. Verify route in Routes.php: 
   $routes->get('/', static function() { return view('landing'); });
2. Check if landing.php exists in app/Views/
3. Clear cache: php spark cache:clear
```

### Issue: Helper functions undefined
```
Solution:
1. Verify notes_helper.php in app/Helpers/
2. Check BaseController has notes_helper in $helpers array
3. Run: php spark cache:clear
```

### Issue: Notes not persisting
```
Solution:
1. Check if notes table exists: php spark migrate
2. Verify session initialized: session_start()
3. Check user_id in session: echo session()->get('user_id');
```

### Issue: Mobile menu not working
```
Solution:
1. Verify Bootstrap JS loaded
2. Check navbar-toggler button exists
3. Clear browser cache
4. Try in incognito mode
```

---

## 🎯 Success Criteria

Landing page dinyatakan SIAP jika:

- [x] Semua 8 sections tampil dengan sempurna
- [x] Responsive di semua device
- [x] Animations smooth tanpa lag
- [x] Login buttons navigate ke /login
- [x] No JavaScript errors di console
- [x] Performance score > 90
- [x] Accessibility score > 90
- [x] Load time < 2 seconds
- [x] Mobile menu works properly
- [x] Notes system integration working

---

## 📸 Screenshots to Verify

Take screenshots of:
1. Landing page on Desktop (1920x1080)
2. Landing page on Mobile (375x667)
3. Landing page on Tablet (768x1024)
4. Mobile menu hamburger open
5. Scrolled to each section
6. Login page after clicking button
7. Student dashboard with notes

---

## 🚀 Next Step After Testing

If all tests pass:
1. **Commit to Git**:
   ```bash
   git add .
   git commit -m "Add landing page & persistent notes system"
   git push origin main
   ```

2. **Deploy to Production**:
   - Update .env for production
   - Run migrations
   - Enable HTTPS
   - Setup CDN (optional)

3. **Monitor**:
   - Check server logs
   - Monitor performance
   - Gather user feedback

---

**Happy Testing! 🎉**

Jika ada pertanyaan atau issues, refer ke DOKUMENTASI_LANDING_PAGE.md

Last Updated: 2026-02-03
