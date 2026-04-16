<?php
require 'vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host = '103.125.181.245';
$user = 'kliksimpus';
$pass = 'KlikSimpusOke!';

$sftp = new SFTP($host);
echo "Menghubungkan ke $host...\n";

if (!$sftp->login($user, $pass)) {
    echo "Login Gagal!\n";
    exit;
}

echo "Login Berhasil.\n";
echo "Direktori saat ini: " . $sftp->pwd() . "\n";
echo "Mencoba menulis file ke upload/test_debug.txt...\n";

if ($sftp->put('upload/test_debug.txt', 'Isi data debug')) {
    echo "Penulisan Berhasil!\n";
} else {
    echo "Penulisan GAGAL.\n";
    echo "Log Terakhir: " . $sftp->getLastError() . "\n";
}
