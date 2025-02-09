<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

A web-based system for uploading, searching, filtering, and managing student records,
built with Laravel add Bootstrap.
Features:
- Secure authentication (Login & Register).
- Upload student data via Excel files (.xlsx, .xls).
- Duplicate prevention during uploads.
- Search and filter students by name and class.
- Responsive UI for teachers.

Prerequisites:
- PHP 8.0 or later
- Composer
- XAMPP (for Apache & MySQL)
- Node.js & npm
- Git (Optional, for version control)
3. Environment Configuration:
cp .env.example .env
(Update the database settings inside .env)
4. Run the Application:
php artisan serve

Visit http://127.0.0.1:8000 in your browser.

Uploading Student Data:
1. Login as a Teacher.
2. Navigate to 'Upload Student Data'.
3. Select an Excel file (.xlsx, .xls).
4. Click 'Upload'.
5. The system automatically skips duplicates.

Search & Filter Students:
1. Go to the student list page.
2. Use the Search Bar to find students by name or class.
3. Use the Filter Dropdown to filter students by class


