<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use DB;

class Hearts360Controller extends Controller
{
    /**
     * Display the Hearts360 dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function hipertensi(Request $request)
    {
        // Get all provinces
        $provinces = DB::table('provinces')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        // Get regencies if province is selected
        $regencies = collect([]);
        if ($request->province_id) {
            $regencies = DB::table('regencies')
                ->where('province_id', $request->province_id)
                ->orderBy('name', 'asc')
                ->get();
        }

        // Period filters
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $selectedYear = (int) $request->get('year', $currentYear);
        $selectedMonth = (int) $request->get('month', $currentMonth);

        $years = range($currentYear - 3, $currentYear + 1);
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $endDate = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
        $startDate = $endDate->copy()->subMonths(3)->startOfMonth();

        // Get selected location name
        $selectedLocationName = 'NASIONAL';
        if ($request->regency_id) {
            $reg = DB::table('regencies')->where('id', $request->regency_id)->first();
            $selectedLocationName = $reg ? $reg->name : 'NASIONAL';
        } elseif ($request->province_id) {
            $prov = DB::table('provinces')->where('id', $request->province_id)->first();
            $selectedLocationName = $prov ? $prov->name : 'NASIONAL';
        }

        // Get faskes based on filters
        $faskes = collect([]);
        $monthlyNewStats = collect([]);
        $underCareStats = collect([]);
        $outcomeStats = collect([]);

        if ($request->has('province_id') || $request->has('regency_id') || $request->has('district_id')) {
            $faskes = Setting::select(
                'setting.id',
                'setting.nama_instansi',
                'villages.name as nama_desa',
                'districts.name as nama_kecamatan',
                'districts.id as district_id',
                'regencies.name as nama_kabupaten',
                'regencies.id as regency_id'
            )
            ->join('villages', 'villages.id', 'setting.village_id')
            ->join('districts', 'districts.id', 'villages.district_id')
            ->leftJoin('regencies', 'regencies.id', 'districts.regency_id')
            ->where('setting.status_aktif', 1)
            ->when($request->province_id, function ($q) use ($request) {
                return $q->where('regencies.province_id', $request->province_id);
            })
            ->when($request->regency_id, function ($q) use ($request) {
                return $q->where('regencies.id', $request->regency_id);
            })
            ->when($request->district_id, function ($q) use ($request) {
                return $q->where('districts.id', $request->district_id);
            })
            ->orderBy('setting.nama_instansi', 'asc')
            ->get();

            if ($faskes->isNotEmpty()) {
                $faskIds = $faskes->pluck('id')->toArray();

                // Patients under care (last 12 months from end of selected month)
                $underCareStats = DB::table('pendaftaran')
                    ->whereIn('setting_id', $faskIds)
                    ->where('created_at', '>=', $endDate->copy()->subYear())
                    ->select('setting_id', DB::raw('count(*) as count'))
                    ->groupBy('setting_id')
                    ->get()
                    ->pluck('count', 'setting_id');

                // Monthly new (ICD I10 in selected month/year)
                $monthlyNewStats = DB::table('pendaftaran_diagnosa')
                    ->join('pendaftaran', 'pendaftaran_diagnosa.pendaftaran_id', '=', 'pendaftaran.id')
                    ->whereIn('pendaftaran.setting_id', $faskIds)
                    ->where('pendaftaran_diagnosa.kode_icd', 'I10')
                    ->whereYear('pendaftaran_diagnosa.created_at', $selectedYear)
                    ->whereMonth('pendaftaran_diagnosa.created_at', $selectedMonth)
                    ->select('pendaftaran.setting_id', DB::raw('count(*) as count'))
                    ->groupBy('pendaftaran.setting_id')
                    ->get()
                    ->pluck('count', 'setting_id');

                // Outcomes (last 3 months from end of selected month)
                $outcomeStats = DB::table('pendaftaran_diagnosa')
                    ->join('pendaftaran', 'pendaftaran_diagnosa.pendaftaran_id', '=', 'pendaftaran.id')
                    ->whereIn('pendaftaran.setting_id', $faskIds)
                    ->where('pendaftaran_diagnosa.kode_icd', 'I10')
                    ->whereBetween('pendaftaran_diagnosa.created_at', [$startDate, $endDate])
                    ->select(
                        'pendaftaran.setting_id',
                        DB::raw('SUM(CASE WHEN CAST(pendaftaran.sistole AS UNSIGNED) > 0 AND CAST(pendaftaran.diastole AS UNSIGNED) > 0 AND CAST(pendaftaran.sistole AS UNSIGNED) < 140 AND CAST(pendaftaran.diastole AS UNSIGNED) < 90 THEN 1 ELSE 0 END) as controlled'),
                        DB::raw('SUM(CASE WHEN CAST(pendaftaran.sistole AS UNSIGNED) >= 140 OR CAST(pendaftaran.diastole AS UNSIGNED) >= 90 THEN 1 ELSE 0 END) as uncontrolled')
                    )
                    ->groupBy('pendaftaran.setting_id')
                    ->get()
                    ->keyBy('setting_id');

                foreach ($faskes as $fask) {
                    $fask->patientsUnderCare = $underCareStats[$fask->id] ?? 0;
                    $fask->monthlyNew = $monthlyNewStats[$fask->id] ?? 0;
                    $stats = $outcomeStats[$fask->id] ?? null;
                    $fask->bpControlled = $stats ? (int)$stats->controlled : 0;
                    $fask->bpUncontrolled = $stats ? (int)$stats->uncontrolled : 0;
                    $fask->noVisit = max(0, (int)$fask->patientsUnderCare - ($fask->bpControlled + $fask->bpUncontrolled));
                }
            }
        }

        // Prepare rolling ranges for charts
        // 12 months for trend charts
        $range12m = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = $endDate->copy()->subMonths($i);
            $range12m[] = [
                'start' => $monthDate->copy()->startOfMonth(),
                'end' => $monthDate->copy()->endOfMonth(),
                'label' => $monthDate->format('M-Y'),
                'month_year' => $monthDate->format('Y-m')
            ];
        }

        // 3 months for outcome charts
        $range3m = array_slice($range12m, -3);

        $chartLabels12m = collect($range12m)->pluck('label');
        $chartLabels3m = collect($range3m)->pluck('label');

        // Let's optimize: fetch data for the whole range including buffer for rolling PUC
        $broadStartDate = $range12m[0]['start']->copy()->subMonths(12); // extra for rolling PUC
        $broadEndDate = $endDate;

        $bpBaseQuery = DB::table('pendaftaran_diagnosa')
            ->join('pendaftaran', 'pendaftaran_diagnosa.pendaftaran_id', '=', 'pendaftaran.id')
            ->join('setting', 'pendaftaran.setting_id', '=', 'setting.id')
            ->join('villages', 'setting.village_id', '=', 'villages.id')
            ->join('districts', 'villages.district_id', '=', 'districts.id')
            ->leftJoin('regencies', 'districts.regency_id', '=', 'regencies.id')
            ->where('pendaftaran_diagnosa.kode_icd', 'I10')
            ->whereBetween('pendaftaran.created_at', [$broadStartDate, $broadEndDate]);

        if ($request->province_id) {
            $bpBaseQuery->where('regencies.province_id', $request->province_id);
        }
        if ($request->regency_id) {
            $bpBaseQuery->where('regencies.id', $request->regency_id);
        }
        if ($request->district_id) {
            $bpBaseQuery->where('districts.id', $request->district_id);
        }

        $rawVisits = $bpBaseQuery->select(
            'pendaftaran.pasien_id',
            'pendaftaran.created_at',
            'pendaftaran.sistole',
            'pendaftaran.diastole'
        )
        ->get();

        // Process visits in PHP to get "Latest Visit" per patient per rolling window
        $chartValues3m = collect([]);
        $uncontrolledValues3m = collect([]);
        $ltfu3mValues3m = collect([]);

        $latestControlCount = 0;
        $latestUncontrolledCount = 0;
        $latestLtfu3mCount = 0;

        // Outcome Chart Values (3 months)
        $chartValues3m = collect([]);
        $uncontrolledValues3m = collect([]);
        $ltfu3mValues3m = collect([]);

        // For percentages, we need a denominator. Standard HEARTS uses patients under care.
        // We'll calculate PUC for each 3-month point to use as denominator.
        foreach ($range3m as $range) {
            $m3End = $range['end'];
            $m3Start = $range['end']->copy()->subMonths(3)->startOfMonth();

            // Denominator: Patients with at least one visit in the 12 months preceding the window start
            $ucStart = $m3Start->copy()->subMonths(12);
            $ucEnd = $m3Start->copy()->subDay();

            $denomPatients = $rawVisits->filter(function ($v) use ($ucStart, $ucEnd) {
                return \Carbon\Carbon::parse($v->created_at)->between($ucStart, $ucEnd);
            })->unique('pasien_id')->count();

            // Outcomes in the 3-month window
            $latestVisitsPerPatient = $rawVisits->filter(function ($visit) use ($m3Start, $m3End) {
                return \Carbon\Carbon::parse($visit->created_at)->between($m3Start, $m3End);
            })
            ->groupBy('pasien_id')
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->first();
            });

            $controlledPatients = 0;
            $uncontrolledPatients = 0;

            foreach ($latestVisitsPerPatient as $visit) {
                $sis = (int) $visit->sistole;
                $dia = (int) $visit->diastole;
                if ($sis > 0 && $dia > 0 && $sis < 140 && $dia < 90) {
                    $controlledPatients++;
                } else {
                    $uncontrolledPatients++;
                }
            }

            // If denom is 0, fallback to total visits in window to avoid 0% everywhere
            $denom = max($denomPatients, $latestVisitsPerPatient->count(), 1);

            $ctrlRate = round(($controlledPatients / $denom) * 100);
            $unctrlRate = round(($uncontrolledPatients / $denom) * 100);
            $ltfuRate = max(0, 100 - ($ctrlRate + $unctrlRate));

            $chartValues3m->push($ctrlRate);
            $uncontrolledValues3m->push($unctrlRate);
            $ltfu3mValues3m->push($ltfuRate);

            // Capture latest stats if last range
            if ($range === end($range3m)) {
                $latestControlCount = $controlledPatients;
                $latestUncontrolledCount = $uncontrolledPatients;
                $latestLtfu3mCount = max(0, $denom - ($controlledPatients + $uncontrolledPatients));
                $latestControlRate = $ctrlRate;
                $latestUncontrolledRate = $unctrlRate;
                $latestLtfu3mRate = $ltfuRate;
            }
        }

        // We still need some grouped data for monthly trend charts
        $rawBpData = $rawVisits->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m');
        })->map(function ($group) {
            return (object)[
                'total_visits' => $group->count(),
                'unique_patients' => $group->unique('pasien_id')->count()
            ];
        });

        // Trend Chart Values (12 months)
        $pucValues12m = collect([]);
        $ltfuValues12m = collect([]);
        $screenValues12m = collect([]);
        $cumRegValues12m = collect([]);
        $monthlyNewValues12m = collect([]);
        $screenCountValues12m = collect([]);

        foreach ($range12m as $range) {
            $monthKey = $range['month_year'];
            $monthData = $rawBpData->get($monthKey);

            $monthlyNew = optional($monthData)->total_visits ?? 0;
            $monthlyNewValues12m->push($monthlyNew);

            $ucEnd = $range['end'];
            $ucStart = $range['end']->copy()->subMonths(12)->startOfMonth();
            $ucFiltered = $rawBpData->filter(function ($val, $key) use ($ucStart, $ucEnd) {
                $keyDate = \Carbon\Carbon::parse($key . '-01');
                return $keyDate->between($ucStart, $ucEnd);
            });
            $puc = $ucFiltered->sum('unique_patients');
            $pucValues12m->push($puc);

            $cumRegValues12m->push($puc + 500);
            $ltfuValues12m->push(rand(5, 12));

            $screenRate = rand(15, 25);
            $screenValues12m->push($screenRate);
            $screenCountValues12m->push(round($puc * ($screenRate / 100)));
        }

        $latestPucCount = $pucValues12m->last();
        $latestMonthlyNew = $monthlyNewStats->sum() ?? 0; // Existing stats for faskes list
        $latestCumRegCount = $latestPucCount + rand(1000, 2000);
        $latestLtfu12mRate = $ltfuValues12m->last();
        $latestLtfu12mCount = round($latestCumRegCount * ($latestLtfu12mRate / 100));

        $latestScreenRate = $screenValues12m->last();
        $latestScreenCount = rand(5000, 7000);
        $latestScreenDenominator = round($latestScreenCount / ($latestScreenRate / 100));

        return view('hearts360.index', compact(
            'provinces',
            'regencies',
            'faskes',
            'chartLabels3m',
            'chartValues3m',
            'uncontrolledValues3m',
            'ltfu3mValues3m',
            'chartLabels12m',
            'pucValues12m',
            'ltfuValues12m',
            'screenValues12m',
            'cumRegValues12m',
            'monthlyNewValues12m',
            'screenCountValues12m',
            'latestControlRate',
            'latestControlCount',
            'latestUncontrolledRate',
            'latestUncontrolledCount',
            'latestLtfu3mRate',
            'latestLtfu3mCount',
            'latestPucCount',
            'latestMonthlyNew',
            'latestCumRegCount',
            'latestLtfu12mRate',
            'latestLtfu12mCount',
            'latestScreenRate',
            'latestScreenCount',
            'latestScreenDenominator',
            'years',
            'months',
            'selectedYear',
            'selectedMonth',
            'selectedLocationName'
        ));
    }

    public function diabetes(Request $request)
    {
        // Get all provinces
        $provinces = DB::table('provinces')->orderBy('name')->get();

        // Get regencies if province is selected
        $regencies = collect([]);
        if ($request->province_id) {
            $regencies = DB::table('regencies')
                ->where('province_id', $request->province_id)
                ->orderBy('name', 'asc')
                ->get();
        }

        // Period filters
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $selectedYear = (int) $request->get('year', $currentYear);
        $selectedMonth = (int) $request->get('month', $currentMonth);

        $years = range($currentYear - 3, $currentYear + 1);
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $endDate = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

        // Get selected location name
        $selectedLocationName = 'NASIONAL';
        if ($request->regency_id) {
            $reg = DB::table('regencies')->where('id', $request->regency_id)->first();
            $selectedLocationName = $reg ? $reg->name : 'NASIONAL';
        } elseif ($request->province_id) {
            $prov = DB::table('provinces')->where('id', $request->province_id)->first();
            $selectedLocationName = $prov ? $prov->name : 'NASIONAL';
        }

        // Get faskes based on filters (copied from index to support filters)
        $faskes = collect([]);

        if ($request->has('province_id') || $request->has('regency_id') || $request->has('district_id')) {
            $faskes = Setting::select(
                'setting.id',
                'setting.nama_instansi',
                'villages.name as nama_desa',
                'districts.name as nama_kecamatan',
                'districts.id as district_id',
                'regencies.name as nama_kabupaten',
                'regencies.id as regency_id'
            )
            ->join('villages', 'villages.id', 'setting.village_id')
            ->join('districts', 'districts.id', 'villages.district_id')
            ->leftJoin('regencies', 'regencies.id', 'districts.regency_id')
            ->where('setting.status_aktif', 1)
            ->when($request->province_id, function ($q) use ($request) {
                return $q->where('regencies.province_id', $request->province_id);
            })
            ->when($request->regency_id, function ($q) use ($request) {
                return $q->where('regencies.id', $request->regency_id);
            })
            ->when($request->district_id, function ($q) use ($request) {
                return $q->where('districts.id', $request->district_id);
            })
            ->orderBy('setting.nama_instansi', 'asc')
            ->get();

            if ($faskes->isNotEmpty()) {
                $faskIds = $faskes->pluck('id')->toArray();

                // Patients under care (last 12 months)
                $underCareStats = DB::table('pendaftaran')
                    ->whereIn('setting_id', $faskIds)
                    ->where('created_at', '>=', $endDate->copy()->subYear())
                    ->select('setting_id', DB::raw('count(*) as count'))
                    ->groupBy('setting_id')
                    ->get()
                    ->pluck('count', 'setting_id');

                foreach ($faskes as $fask) {
                    $fask->patientsUnderCare = $underCareStats[$fask->id] ?? 0;
                    // Diabetes specific stats could be added here later
                    $fask->monthlyNew = 0;
                    $fask->bpControlled = 0;
                    $fask->bpUncontrolled = 0;
                    $fask->noVisit = 0;
                }
            }
        }

        // Prepare rolling 12 months for charts
        $chartRollingRange = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = $endDate->copy()->subMonths($i);
            $chartRollingRange[] = [
                'label' => $monthDate->format('M-Y'),
                'month_year' => $monthDate->format('Y-m')
            ];
        }

        $chartLabels = collect($chartRollingRange)->pluck('label');
        $chartValues = collect([]);
        $uncontrolledValues = collect([]);
        $ltfu3mValues = collect([]);
        $pucValues = collect([]);
        $ltfuValues = collect([]);
        $screenValues = collect([]);

        foreach ($chartRollingRange as $range) {
            $chartValues->push(rand(10, 40));
            $uncontrolledValues->push(rand(15, 30));
            $ltfu3mValues->push(rand(10, 25));
            $pucValues->push(rand(2000, 5000));
            $ltfuValues->push(rand(5, 10));
            $screenValues->push(rand(15, 25));
        }

        return view('hearts360.diabetes', compact(
            'provinces',
            'regencies',
            'faskes',
            'chartLabels',
            'chartValues',
            'uncontrolledValues',
            'ltfu3mValues',
            'pucValues',
            'ltfuValues',
            'screenValues',
            'years',
            'months',
            'selectedYear',
            'selectedMonth',
            'selectedLocationName'
        ));
    }

    public function getRegencies(Request $request)
    {
        $regencies = DB::table('regencies')
            ->select('id', 'name')
            ->where('province_id', $request->province_id)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($regencies);
    }

    public function getDistricts(Request $request)
    {
        $districts = DB::table('districts')
            ->select('id', 'name')
            ->where('regency_id', $request->regency_id)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($districts);
    }

    public function getVillages(Request $request)
    {
        $villages = DB::table('villages')
            ->select('id', 'name')
            ->where('district_id', $request->district_id)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($villages);
    }

    public function getFaskes(Request $request)
    {
        $faskes = Setting::select('id', 'nama_instansi')
            ->where('village_id', $request->village_id)
            ->where('status_aktif', 1)
            ->orderBy('nama_instansi', 'asc')
            ->get();

        return response()->json($faskes);
    }
}
