<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\H360Data;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $recentData = H360Data::orderBy('encounter_date', 'desc')->limit(10)->get();
        return view('uploader.index', compact('recentData'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // Baca header

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            
            H360Data::updateOrCreate(
                ['encounter_id' => $data['encounter_id']],
                [
                    'patient_id' => $data['patient_id'],
                    'full_name' => $data['full_name'],
                    'phone_number' => $data['phone_number'] ?? null,
                    'address' => $data['address'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'encounter_date' => $data['encounter_date'],
                    'systole' => $data['systole'] ?? null,
                    'diastole' => $data['diastole'] ?? null,
                    'sugar_level' => $data['sugar_level'] ?? null,
                    'icd_code' => $data['icd_code'] ?? null,
                    'diagnosis_name' => $data['diagnosis_name'] ?? null,
                    'next_appointment' => $data['next_appointment'] ?? null,
                ]
            );
            $count++;
        }
        fclose($handle);

        return back()->with('success', "Berhasil memproses $count data pasien.");
    }
}
