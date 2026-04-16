#!/bin/bash

# KlikSimpus x HEARTS360 Auto-Deployment Script
# ============================================

set -e

echo "🚀 Memulai Deployment KlikSimpus x HEARTS360..."

# 1. Deteksi Docker
if ! [ -x "$(command -v docker)" ]; then
  echo "⚠️ Docker tidak ditemukan. Menginstal Docker..."
  curl -fsSL https://get.docker.com -o get-docker.sh
  sudo sh get-docker.sh
  sudo usermod -aG docker $USER
  echo "✅ Docker berhasil diinstal."
else
  echo "✅ Docker sudah terpasang."
fi

# 2. Deteksi Docker Compose
if ! [ -x "$(command -v docker-compose)" ]; then
  echo "⚠️ Docker Compose tidak ditemukan. Menginstal..."
  sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
  sudo chmod +x /usr/local/bin/docker-compose
  echo "✅ Docker Compose berhasil diinstal."
fi

# 3. Setup Folder & Clone Repository
PROJECT_DIR="hearts360-integration"
REPO_URL="https://github.com/bestariteknologi-lgtm/HEARTS360.git"

if [ -d "$PROJECT_DIR" ]; then
  echo "📂 Folder project sudah ada. Melakukan update (git pull)..."
  cd "$PROJECT_DIR"
  git pull origin main
else
  echo "🌐 Menyalin repository dari GitHub..."
  git clone "$REPO_URL" "$PROJECT_DIR"
  cd "$PROJECT_DIR"
fi

# 4. Inisialisasi Environment (Jika belum ada)
if [ ! -f ".env" ]; then
  echo "📝 Membuat file .env dari template..."
  cp .env.example .env
  echo "⚠️ PERHATIAN: Silakan edit file .env untuk kredensial sFTP Anda!"
fi

# 5. Jalankan Container
echo "🐳 Menjalankan layanan via Docker Compose..."
docker-compose up -d --build

echo "===================================================="
echo "🎉 DEPLOYMENT SELESAI!"
echo "===================================================="
echo "• Dashboard: http://dashboards.yourdomain.com"
echo "• sFTP Port: 22 (Pastikan Firewall terbuka)"
echo "• Status Container: "
docker ps | grep kliksimpus
echo "===================================================="
