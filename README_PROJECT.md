# Digital Content Selling Platform (Laravel 12)

A production-ready, secure digital content selling platform for assignments, e-books, and notes.

## 🚀 Quick Start (Local)

1. **Configure Environment**:
   ```bash
   cp .env.example .env
   # Update DB_DATABASE, DB_USERNAME, DB_PASSWORD
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Generate Key & Migrate**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

4. **Run Server**:
   ```bash
   php artisan serve
   ```

## 🌐 Hostinger Deployment

1. **Upload Files**: Upload all files to your Hostinger `public_html` or a subdirectory.
2. **Move Public Content**: If your domain points to `public_html`, move contents of `public/*` to `public_html` and adjust `index.php` paths. Alternatively, use the included `.htaccess`.
3. **Storage Permission**: Ensure `storage/` and `bootstrap/cache/` are writable.
4. **Private Storage**: Product files are securely stored in `storage/app/private` and are NOT accessible via direct URL.

## 🛡️ Security Features

- **Signed URLs**: Content access is granted via temporary 30-minute signed URLs.
- **Secure Viewer**: Integrated PDF.js viewer with disabled print/download/copy.
- **Right-Click Protection**: JS and CSS prevent content theft on viewer pages.
- **Watermarking**: Dynamic overlay of user email on secure content.
- **Role Control**: Admin-only access to uploads and user management.

## 📱 Mobile-First Design

Built using **Tailwind CSS v3** with a custom responsive utility system:
- **Mobile**: Touch-friendly buttons, bottom navigation, and drawers.
- **Desktop**: Sidebar-driven admin panel and grid-based product browsing.

## 🛠️ Tech Stack

- **Laravel 12**
- **Tailwind CSS v3** (via CDN + JIT)
- **Spatie Permissions** (Roles: Super Admin, User)
- **Intervention Image** (Banner processing)
- **PDF.js** (Secure viewing)

## 🔑 Admin Credentials (Default)
- **Email**: `admin@example.com`
- **Password**: `password`

## 🔑 Demo User Credentials
- **Email**: `student@example.com`
- **Password**: `password`
