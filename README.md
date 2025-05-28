# Excel Mapper

**Excel Mapper** is a Laravel package that provides a simple UI and backend logic to import Excel files into your database with customizable column mapping. It is built with Laravel Livewire and supports dynamic table selection, column mapping, and validation.

---

## 🚀 Features

- Upload Excel/CSV files via Livewire.
- Map Excel columns to database table columns.
- Automatically normalize column names.
- Display mapped data before importing.
- Supports row-level validation.
- Rules can be defined per table using the `config/excel-import.php` file.

---

## 📦 Installation

```bash
composer require sam/excel-mapper
php artisan vendor:publish --provider="Sam\ExcelMapper\ExcelMapperServiceProvider" 
php artisan vendor:publish --tag=excel-mapper-config # Publish the config file
```
## 📄 Configuration
```bash
php artisan vendor:publish --tag=excel-mapper-config # Publish the config file
```
## 🧪Validation
You can define validation rules for each table in the `config/excel-import.php` file. The rules will be applied to the data before importing it into the database.

