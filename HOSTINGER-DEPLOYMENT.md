# Hostinger Deployment Checklist

## Pre-Deployment

- [ ] Ensure all environment variables are set in `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set proper `APP_URL` (e.g., `https://yourdomain.com`)

## File Upload

- [ ] Upload all project files to `public_html` or your domain directory
- [ ] Ensure `storage` and `bootstrap/cache` directories are writable (755 permissions)
- [ ] Verify `.htaccess` file exists in the public directory

## Storage Configuration

### Critical: Create Storage Symlink

The most common issue with file uploads not displaying is a missing storage symlink.

**Option 1: Via SSH (Recommended)**
```bash
cd /home/username/public_html
php artisan storage:link
```

**Option 2: Via File Manager**
1. Navigate to `public_html/public/`
2. Create a symbolic link named `storage`
3. Point it to `../storage/app/public`

**Option 3: Manual PHP Script (if SSH not available)**
Create a file `create-symlink.php` in your public directory:
```php
<?php
symlink('../storage/app/public', 'storage');
echo 'Storage link created!';
?>
```
Visit `https://yourdomain.com/create-symlink.php` then delete the file.

### Verify Storage Structure

Ensure these directories exist with proper permissions:
```
storage/
├── app/
│   ├── public/          (755)
│   │   ├── banners/     (755)
│   │   └── products/    (755)
├── framework/
│   ├── cache/           (755)
│   ├── sessions/        (755)
│   └── views/           (755)
└── logs/                (755)
```

## Post-Deployment Commands

Run these commands via SSH or Hostinger's terminal:

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

## Verification Steps

### 1. Check Storage Link
- Navigate to `https://yourdomain.com/storage`
- You should NOT see a 404 error
- If you see a directory listing or forbidden error, the symlink works

### 2. Test Banner Upload
- Login to admin panel
- Upload a test banner
- Check if the banner appears in the admin list
- Visit the homepage to verify the banner displays

### 3. Check File Permissions
Via File Manager or SSH:
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 4. Debug Banner URLs
- Right-click on the banner area → Inspect Element
- Check the `<img>` tag's `src` attribute
- It should look like: `https://yourdomain.com/storage/banners/filename.jpg`
- Try accessing that URL directly in a new tab

### 5. Check Browser Console
- Open Developer Tools (F12)
- Go to Console tab
- Look for any errors related to image loading
- Check Network tab for failed requests (404, 403, 500 errors)

## Common Issues & Solutions

### Issue: Banner shows in admin but not on homepage
**Solution:** Check if banner is marked as "Active" in admin panel

### Issue: 404 error on banner images
**Solution:** Storage symlink is missing. Follow "Create Storage Symlink" steps above

### Issue: 403 Forbidden error
**Solution:** File permissions issue
```bash
chmod 644 storage/app/public/banners/*
chmod 755 storage/app/public/banners
```

### Issue: Images work locally but not on Hostinger
**Solution:** 
1. Verify `APP_URL` in `.env` matches your domain
2. Clear config cache: `php artisan config:clear`
3. Recreate storage symlink

### Issue: Blank/broken image icon
**Solution:**
1. Check browser console for the actual error
2. Verify the file exists in `storage/app/public/banners/`
3. Check file permissions (should be 644)

## Database Verification

Check the `banners` table:
```sql
SELECT id, image_path, title, is_active FROM banners;
```

Ensure:
- `image_path` contains paths like `banners/filename.jpg` (NOT full URLs)
- `is_active` is set to `1` for banners that should display

## Security Checklist

- [ ] `.env` file is NOT publicly accessible
- [ ] `APP_DEBUG=false` in production
- [ ] Database credentials are secure
- [ ] File permissions are properly set (not 777)
- [ ] SSL certificate is installed and working

## Performance Optimization

- [ ] Enable OPcache in PHP settings
- [ ] Configure caching in Hostinger control panel
- [ ] Use CDN for static assets (optional)
- [ ] Optimize images before uploading

## Maintenance

### Regular Tasks
- Monitor `storage/logs/laravel.log` for errors
- Backup database regularly
- Keep Laravel and dependencies updated
- Clear caches after code changes

### After Uploading New Code
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
