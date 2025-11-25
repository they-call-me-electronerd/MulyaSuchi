# Quick Start Guide - Testing Search & Filter

## How to Test

### Start Your Server

1. **Make sure XAMPP is running:**
   - Apache server should be active
   - MySQL server should be active

2. **Open the products page:**
   ```
   http://localhost/MulyaSuchi/public/products.php
   ```

### Test Search Feature

#### Desktop:
1. **Sidebar Search:**
   - Type in the search box on the left sidebar
   - Results update automatically after you stop typing (300ms delay)
   - Try: "apple", "banana", "rice"

2. **Header Search:**
   - Type in the search box at the top center
   - Should work the same as sidebar search
   - Both inputs stay synchronized

#### Mobile:
1. Open on mobile or resize browser to < 768px
2. Click "Filters" button
3. Type in search box
4. Click "Apply Filters"
5. Filter panel closes automatically

### Test Category Filter

1. **Select a category** from the dropdown (e.g., "Fruits", "Vegetables")
2. Products filter instantly
3. Results count updates
4. Try combining with search

### Test Price Range

1. **Enter minimum price:** e.g., 50
2. **Enter maximum price:** e.g., 500
3. Results update automatically
4. Try edge cases:
   - Only min price
   - Only max price
   - Both prices
   - Clear prices

### Test Sorting

1. Change sort option:
   - Name (A-Z) - default
   - Name (Z-A)
   - Price (Low to High)
   - Price (High to Low)
   - Newest First
   - Oldest First

2. Products reorder immediately

### Test Combinations

1. **Search + Category:**
   - Search "rice"
   - Select "Groceries" category
   
2. **Category + Price Range:**
   - Select "Fruits"
   - Set price 100-300

3. **All Filters:**
   - Search "organic"
   - Category "Vegetables"
   - Price 50-200
   - Sort by "Price (Low to High)"

### Test Reset

1. Apply multiple filters
2. Click "Reset" button
3. All filters clear
4. Returns to default view (all products, sorted A-Z)

### Test Pagination

1. Scroll to bottom
2. Click page numbers
3. Page scrolls to top smoothly
4. Products load for selected page
5. URL updates with page number

### Test Responsive Design

#### Desktop (> 992px):
- Sidebar visible on left
- 3-4 column product grid
- Full search bar in header

#### Tablet (768px - 992px):
- Filter toggle button appears
- 2-3 column product grid
- Sidebar becomes slide-in panel

#### Mobile (< 768px):
- Filter toggle button in header
- Single column product grid
- Full-width filter panel
- Overlay when filter is open
- 44px minimum touch targets

### Test Dark Mode

1. Toggle dark mode (if available in your theme)
2. All filters should adapt
3. Colors should remain readable
4. Contrasts should be maintained

## Expected Behavior

### ✅ What Should Work:

1. **Search:** Live updates, no page reload
2. **Filters:** Instant filtering without refresh
3. **Pagination:** Smooth page transitions
4. **Mobile:** Filter panel slides in/out
5. **Loading:** Spinner appears during data fetch
6. **URL:** Updates with current filters
7. **Empty State:** Shows when no results found
8. **Error State:** Shows on network/server error

### ❌ Common Issues & Solutions:

**Issue:** "No products found" always shows
- **Check:** Database has products with status = 'active'
- **Check:** Console for JavaScript errors
- **Fix:** Run SQL seed data if needed

**Issue:** Search doesn't work
- **Check:** Browser console for errors
- **Check:** Network tab for AJAX calls
- **Check:** `public/ajax/filter_products.php` exists
- **Fix:** Verify file permissions

**Issue:** Filters don't apply
- **Check:** products.js is loaded (view source)
- **Check:** No JavaScript errors in console
- **Fix:** Hard refresh (Ctrl+F5)

**Issue:** Mobile filter won't open
- **Check:** Screen width is < 992px
- **Check:** JavaScript is enabled
- **Fix:** Clear cache and reload

**Issue:** Styling looks broken
- **Check:** `assets/css/pages/products.css` is loaded
- **Check:** Browser cache
- **Fix:** Hard refresh

## Browser Developer Tools

### Check Console:
```
F12 → Console Tab
```
Look for:
- "✨ Products page with AJAX filtering loaded successfully!"
- Any errors (red text)

### Check Network:
```
F12 → Network Tab → XHR
```
When filtering, you should see:
- Request to `filter_products.php`
- Status 200
- Response with JSON data

### Check Response:
Click on the AJAX request → Preview tab
Should see:
```json
{
  "success": true,
  "items": [...],
  "pagination": {
    "current_page": 1,
    "total_pages": 4,
    "total_items": 113
  }
}
```

## Mobile Testing

### Chrome DevTools:
1. Press F12
2. Click device toolbar icon (Ctrl+Shift+M)
3. Select device: iPhone 12, Galaxy S20, etc.
4. Test all features

### Real Device Testing:
1. Find your computer's IP: `ipconfig` (Windows) or `ifconfig` (Mac/Linux)
2. On mobile, visit: `http://YOUR_IP/MulyaSuchi/public/products.php`
3. Test filter button, search, and responsiveness

## Performance Testing

### Speed Test:
1. Open DevTools → Network
2. Type in search box
3. Count AJAX requests
4. Should be 1 request per search (after 300ms delay)

### Loading Test:
1. Apply filter
2. Loading spinner should appear
3. Products should fade in
4. Total time < 1 second (on localhost)

## Accessibility Testing

### Keyboard Navigation:
1. Press Tab to navigate through filters
2. Enter to apply/reset
3. Arrow keys in dropdowns
4. All focusable elements should have visible focus

### Screen Reader Test (Optional):
1. Enable screen reader (NVDA, JAWS, etc.)
2. Navigate through filters
3. Labels should be announced
4. Button purposes should be clear

## Final Checklist

Before considering it "done":

- [ ] Search works (sidebar)
- [ ] Search works (header)
- [ ] Both searches are synchronized
- [ ] Category filter works
- [ ] Price min filter works
- [ ] Price max filter works
- [ ] Sort dropdown works
- [ ] Apply button works
- [ ] Reset button works
- [ ] Pagination works
- [ ] Mobile filter toggle works
- [ ] Filter panel slides in/out on mobile
- [ ] Responsive grid (1, 2, 3 columns)
- [ ] Loading spinner appears
- [ ] Empty state shows when appropriate
- [ ] URL updates with filters
- [ ] No console errors
- [ ] Dark mode works (if applicable)
- [ ] Touch targets ≥ 44px on mobile

## Need Help?

1. **Check documentation:** `docs/PRODUCTS_SEARCH_FILTER_FIX.md`
2. **Review code comments:** All files are well-commented
3. **Check console:** JavaScript errors will appear here
4. **Network tab:** Verify AJAX calls are successful
5. **Database:** Ensure products exist with status='active'

---

**Happy Testing!** 🎉

All features should now work seamlessly across desktop, tablet, and mobile devices.
