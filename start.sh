#!/bin/bash

# 1. Jalankan Migrasi Database agar tabel h360_data terbuat otomatis
echo "Running migrations..."
php artisan migrate --force

# 2. Jalankan Sync pertama kali saat start
echo "Running initial sync..."
php artisan hearts360:sync &

# 3. Nyalakan Web Server untuk melayani upload manual (Gateway)
echo "Starting Web Gateway on port 80..."
php artisan serve --host=0.0.0.0 --port=80
