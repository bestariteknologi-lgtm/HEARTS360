<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\H360IntegrationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Hearts360Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hearts360:sync {--months=12 : Jarak waktu data ditarik (bulan)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data pasien Hipertensi/Diabetes ke server HEARTS360';

    protected $service;

    public function __construct(H360IntegrationService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai ekstraksi data dari database Klikmedis...');
        
        $months = $this->option('months');
        $rawRecords = $this->service->getLongitudinalData($months);
        
        if ($rawRecords->isEmpty()) {
            $this->warn('Tidak ada data pasien yang ditemukan untuk periode tersebut.');
            return 0;
        }

        $this->info('Ditemukan ' . $rawRecords->count() . ' catatan medis.');

        $csvContent = $this->service->formatToCsv($rawRecords);
        
        $fileName = 'hearts360_export_' . now()->format('Ymd_His') . '.csv';
        
        // Simpan lokal sementara
        Storage::disk('local')->put('exports/' . $fileName, $csvContent);
        $this->info('Data berhasil diekspor ke local: storage/app/exports/' . $fileName);

        $this->line('--------------------------------------------------');
        $this->info('MENYIAPKAN PENGIRIMAN sFTP...');
        
        try {
            $uploaded = Storage::disk('sftp')->put($fileName, $csvContent);
            if ($uploaded) {
                $this->info('BERHASIL: File telah terkirim ke server HEARTS360.');
            } else {
                $this->error('GAGAL: File gagal dikirim ke server sFTP.');
            }
        } catch (\Exception $e) {
            $this->error('ERROR sFTP: ' . $e->getMessage());
            $this->warn('Tips: Pastikan kredensial di .env sudah benar dan port 22 terbuka.');
        }

        $this->info('Proses selesai.');

        return 0;
    }
}
