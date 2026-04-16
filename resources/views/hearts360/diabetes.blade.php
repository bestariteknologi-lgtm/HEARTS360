<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ __('messages.title_diabetes') }}</title>
    <meta name="description"
        content="A free, open source, template for creating a very useful hypertension and diabetes dashboard to support public health projects." />
    <meta name="author" content="Resolve to Save Lives" />

    <link rel="icon" href="{{ asset('favicon.png') }}" />

    <link rel="stylesheet" href="{{ asset('css/hearts360/preflight.css') }}?v=1.0" />
    <link rel="stylesheet" href="{{ asset('css/hearts360/template.css') }}" />
</head>

<body>
    <div class="banner">
        <div class="banner-body">
            <div class="region-filters">
                <div class="region-nav" style="display: none;">
                    <div class="hover-button">{{ __('messages.choose_region') }}</div>

                    <div class="card nav-hover-content">
                        <div class="hover-button hover-button-hover-state">
                            {{ __('messages.choose_region') }}
                        </div>

                        <form class="search-form">
                            <input id="region-search" type="search" class="region-search"
                                placeholder="{{ __('messages.search_placeholder') }}" />
                        </form>
                        <dl id="search-results" class="search-results hidden">
                            <dt class="search-section">{{ __('messages.state') }}</dt>
                            <dd class="results-state">
                                <a href="#"> <em>Ap</em>ple State </a>
                            </dd>
                            <dd class="results-state">
                                <a href="#"> <em>Ap</em>ington State </a>
                            </dd>
                            <dt class="search-section">{{ __('messages.district') }}</dt>
                            <dd class="results-state">
                                <a href="#">
                                    Crab<em>ap</em>ple District <span>(Granite State)</span>
                                </a>
                            </dd>
                            <dd class="results-state">
                                <a href="#">
                                    Red <em>Ap</em>ple District <span>(Apple State)</span>
                                </a>
                            </dd>
                            <dt class="search-section">{{ __('messages.sub_district') }}</dt>
                            <dd class="results-state">
                                <a href="#">
                                    Crab<em>ap</em>ple Hills <span>(Water State)</span>
                                </a>
                            </dd>
                            <dd class="results-state">
                                <a href="#">
                                    Crab<em>ap</em>ple Mountains <span>(Water State)</span>
                                </a>
                            </dd>
                        </dl>
                        <ul id="myUL" class="nav-list">
                            <li class="region-national"><a href="#">{{ __('messages.national') }}</a></li>
                            @if (isset($provinces) && count($provinces) > 0)
                                @foreach ($provinces as $province)
                                    <li class="region-state">
                                        <span class="caret toggle-region" data-type="regency"
                                            data-parent-id="{{ $province->id }}"></span>
                                        <a href="#">{{ $province->name }}</a>
                                        <ul class="nested" id="province-{{ $province->id }}">
                                            <!-- Regencies will be loaded here -->
                                        </ul>
                                    </li>
                                @endforeach
                            @else
                                <li class="region-state">
                                    <span class="caret"></span><a href="#">{{ __('messages.no_data') }}</a>
                                    <ul class="nested">
                                        <li class="region-facility">
                                            <a href="#">{{ __('messages.data_unavailable') }}</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="region-nav">
                    <select id="province-select" class="hover-button"
                        style="background: rgba(255, 255, 255, 0.2); border: none; color: white; padding: 8px 10px; border-radius: 4px; font-weight: 600; cursor: pointer; -webkit-appearance: none; -moz-appearance: none; appearance: none; width: 100%; min-width: 150px;">
                        <option value="" style="color: #333;">{{ __('messages.choose_province') }}</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province->id }}" style="color: #333;"
                                {{ request('province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="region-nav" id="regency-nav"
                    style="{{ count($regencies) > 0 ? '' : 'display:none;' }}">
                    <select id="regency-select" class="hover-button"
                        style="background: rgba(255, 255, 255, 0.2); border: none; color: white; padding: 8px 10px; border-radius: 4px; font-weight: 600; cursor: pointer; -webkit-appearance: none; -moz-appearance: none; appearance: none; width: 100%; min-width: 150px;">
                        <option value="" style="color: #333;">{{ __('messages.choose_regency') }}</option>
                        @foreach ($regencies as $regency)
                            <option value="{{ $regency->id }}" style="color: #333;"
                                {{ request('regency_id') == $regency->id ? 'selected' : '' }}>{{ $regency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="region-nav">
                    <select id="year-select" class="hover-button"
                        style="background: rgba(255, 255, 255, 0.2); border: none; color: white; padding: 8px 10px; border-radius: 4px; font-weight: 600; cursor: pointer; -webkit-appearance: none; -moz-appearance: none; appearance: none; width: 100%;">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" style="color: #333;"
                                {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="region-nav">
                    <select id="month-select" class="hover-button"
                        style="background: rgba(255, 255, 255, 0.2); border: none; color: white; padding: 8px 10px; border-radius: 4px; font-weight: 600; cursor: pointer; -webkit-appearance: none; -moz-appearance: none; appearance: none; width: 100%;">
                        @foreach ($months as $mNum => $mName)
                            <option value="{{ $mNum }}" style="color: #333;"
                                {{ $selectedMonth == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h1>
                <div class="logo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill-opacity="1.0" fill="white">
                        <path
                            d="M16.5 3C19.5376 3 22 5.5 22 9C22 16 14.5 20 12 21.5C10.0226 20.3135 4.91699 17.563 2.86894 13.001L1 13V11L2.21045 11.0009C2.07425 10.3633 2 9.69651 2 9C2 5.5 4.5 3 7.5 3C9.35997 3 11 4 12 5C13 4 14.64 3 16.5 3ZM16.5 5C15.4241 5 14.2593 5.56911 13.4142 6.41421L12 7.82843L10.5858 6.41421C9.74068 5.56911 8.5759 5 7.5 5C5.55906 5 4 6.6565 4 9C4 9.68542 4.09035 10.3516 4.26658 11.0004L6.43381 11L8.5 7.55635L11.5 12.5563L12.4338 11H17V13H13.5662L11.5 16.4437L8.5 11.4437L7.56619 13L5.10789 13.0006C5.89727 14.3737 7.09304 15.6681 8.64514 16.9029C9.39001 17.4955 10.1845 18.0485 11.0661 18.6038C11.3646 18.7919 11.6611 18.9729 12 19.1752C12.3389 18.9729 12.6354 18.7919 12.9339 18.6038C13.8155 18.0485 14.61 17.4955 15.3549 16.9029C18.3337 14.533 20 11.9435 20 9C20 6.64076 18.463 5 16.5 5Z">
                        </path>
                    </svg>
                </div>
                <span>HEARTS</span>360
            </h1>


            <div class="link-list">
                <span
                    style="
              padding: 2px 4px;
              background: #3063c2;
              display: inline-block;
              height: 100%;
              color: white;
              border-radius: 3px;
              font-size: 11px;
            ">v1.3</span>
                <a href="{{ route('lang.switch', 'id') }}"
                    style="{{ app()->getLocale() == 'id' ? 'font-weight:bold; text-decoration:underline;' : 'opacity:0.7;' }}">ID</a>
                <span style="color:rgba(255,255,255,0.5)">|</span>
                <a href="{{ route('lang.switch', 'en') }}"
                    style="{{ app()->getLocale() == 'en' ? 'font-weight:bold; text-decoration:underline;' : 'opacity:0.7;' }}">EN</a>
                <a href="about.html">About</a>
                <a class="external-link desktop-only"
                    href="https://github.com/simpledotorg/hearts360#developer-guide">Developer guide</a>
                <a class="external-link" href="https://github.com/simpledotorg/hypertension-dashboard">GitHub</a>
            </div>
        </div>
    </div>
    <div class="link-dashboards">
        <ul>
            <li>
                <a href="{{ route('hearts360.hipertensi', request()->query()) }}">
                    {{ __('messages.hypertension') }} <span class="desktop-only">{{ __('messages.dashboard') }}</span>
                </a>
            </li>
            <li class="active-link">
                <a href="{{ route('hearts360.diabetes', request()->query()) }}">
                    {{ __('messages.diabetes') }} <span class="desktop-only">{{ __('messages.dashboard') }}</span>
                </a>
            </li>
            <li>
                <a href="#">
                    {{ __('messages.overdue') }} <span class="desktop-only">{{ __('messages.patients_list') }}</span>
                </a>
            </li>
        </ul>
    </div>

    <main class="main">
        <div class="header">
            <h1>{{ $selectedLocationName }}</h1>
            <div class="date-updated">Data last updated: {{ now()->format('d-M-Y') }}</div>
        </div>

        <!-- Section 1 -->
        <h2 class="columns-header">{{ __('messages.cvd_dm_indicators') }}</h2>

        <div class="columns-3">
            <div class="card">
                <div class="heading">
                    <h3>
                        {{ __('messages.dm_patients_ctrl_bp') }}
                    </h3>
                    <div class="figures">
                        <p class="large-num bp-controlled">4,808</p>
                        <div class="detail">
                            <p>{{ __('messages.patients_with_bp_ctrl', ['count' => 4808]) }}</p>
                        </div>
                    </div>
                    <div class="chart" style="height: 300px">
                        <canvas id="patientsprotected"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="heading">
                    <h3>
                        {{ __('messages.dm_patients_statins') }}
                    </h3>
                    <div class="figures">
                        <p class="large-num" style="color:#6c757d">15%</p>
                        <div class="detail">
                            <p>1,594 patients prescribed statins</p>
                        </div>
                    </div>
                    <div class="chart" style="height: 300px">
                        <canvas id="dmStatins"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2 -->
        <h2 class="columns-header spacer">{{ __('messages.prog_mgmt_indicators') }}</h2>

        <div class="columns-3">
            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.dm_treatment_cascade') }}</h3>
                    <p>
                        {{ __('messages.dm_cascade_desc', ['location' => $selectedLocationName]) }}
                    </p>
                </div>
                <div class="body">
                    <div class="coverage">
                        <div class="coverage-column">
                            <div class="coverage-bar">
                                <div class="coverage-bar-fill" style="height: 100%">
                                    <p class="coverage-estimated">100%</p>
                                </div>
                            </div>
                            <p>250,800</p>
                            <p class="text-grey label-small">
                                {{ __('messages.est_people_dm') }}
                            </p>
                        </div>
                        <div class="coverage-column">
                            <div class="coverage-bar">
                                <div class="coverage-bar-fill registrations-bg" style="height: 5%">
                                    <p class="coverage-number registrations">5%</p>
                                </div>
                            </div>
                            <p>12,213</p>
                            <p class="text-grey label-small">
                                {{ __('messages.cumulative_reg') }}
                            </p>
                        </div>
                        <div class="coverage-column">
                            <div class="coverage-bar">
                                <div class="coverage-bar-fill under-care-bg" style="height: 4%">
                                    <p class="coverage-number under-care">4%</p>
                                </div>
                            </div>
                            <p>10,632</p>
                            <p class="text-grey label-small">{{ __('messages.patients_uc') }}</p>
                        </div>
                        <div class="coverage-column">
                            <div class="coverage-bar">
                                <div class="coverage-bar-fill bp-controlled-bg" style="height: 2%">
                                    <p class="coverage-number bp-controlled">2%</p>
                                </div>
                            </div>
                            <p>4,808</p>
                            <p class="text-grey label-small">{{ __('messages.patients_bs_ctrl') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card col-span-2">
                <div class="heading">
                    <h3>{{ __('messages.dm_treatment_outcomes') }}</h3>
                    <p>
                        {{ __('messages.outcomes_desc', ['count' => '7,882', 'date' => '1-May-2024', 'start_date' => '1-May-2024', 'end_date' => '31-Jul-2024']) }}
                    </p>

                    <div class="body columns-3">
                        <div class="inner-card">
                            <div class="figures">
                                <h4 class="bp-controlled">
                                    {{ __('messages.bs_ctrl_latest') }}
                                </h4>
                                <p class="large-num bp-controlled">{{ $latestControlRate ?? 58 }}%</p>
                                <div class="detail">
                                    <p>{{ __('messages.patients_bs_ctrl', ['count' => number_format($latestControlCount ?? 4571)]) }}
                                    </p>
                                </div>
                            </div>
                            <div class="chart">
                                <canvas id="bpcontrolled"></canvas>
                            </div>
                        </div>
                        <div class="inner-card">
                            <div class="figures">
                                <h4 class="bp-uncontrolled">
                                    {{ __('messages.bs_unctrl_latest') }}
                                </h4>
                                <p class="large-num bp-uncontrolled">17%</p>
                                <div class="detail">
                                    <p>1,340 patients with blood sugar uncontrolled</p>
                                </div>
                            </div>
                            <div class="chart">
                                <canvas id="bpuncontrolled"></canvas>
                            </div>
                        </div>
                        <div class="inner-card">
                            <div class="figures">
                                <h4 class="three-month-ltfu">{{ __('messages.no_visit_3mo') }}</h4>
                                <p class="large-num three-month-ltfu">22%</p>
                                <div class="detail">
                                    <p>{{ __('messages.patients_with_no_visit', ['count' => '1,734']) }}</p>
                                </div>
                            </div>
                            <div class="chart">
                                <canvas id="ltfu3Month"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns-3 spacer">
            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.puc_bold') }}</h3>
                    <p>
                        {{ __('messages.puc_subtext') }}
                    </p>
                </div>
                <div class="body">
                    <div class="figures">
                        <div>
                            <p class="large-num under-care">10,632</p>
                            <div class="detail">
                                <p>{{ __('messages.patients_reg_in', ['count' => '1,043', 'date' => 'Jul-2024']) }}</p>
                                <p class="text-grey">
                                    <span
                                        class="registrations">{{ __('messages.of_cumulative_reg', ['count' => '12,213']) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="registrations"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.ltfu_12mo') }}</h3>
                    <p>
                        {{ __('messages.ltfu_card_text') }}
                    </p>
                </div>
                <div class="body">
                    <div class="figures">
                        <p class="large-num twelve-month-ltfu">10%</p>
                        <div class="detail">
                            <p>{{ __('messages.ltfu_chart_text', ['count' => '1,562', 'start_date' => 'Aug-2023', 'end_date' => 'Jul-2024']) }}
                            </p>
                            <p class="text-grey">
                                <span
                                    class="registrations">{{ __('messages.of_cumulative_reg', ['count' => '12,213']) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="ltfu12Months"></canvas>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.opp_screening') }}</h3>
                    <p>{{ __('messages.scr_card_text') }}</p>
                </div>
                <div class="body">
                    <div class="figures">
                        <div>
                            <p class="large-num" style="color: #34aea0">23%</p>
                            <div class="detail">
                                <p>{{ __('messages.scr_chart_text', ['count' => '6,900', 'date' => 'Jul-2024']) }}</p>
                                <p class="text-grey">
                                    {{ __('messages.scr_est_text', ['count' => '30,000']) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="screenings"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card spacer">
            <div class="heading">
                <h3>{{ __('messages.compare_sub_regions') }}</h3>
                <p>
                    {{ __('messages.compare_desc', ['start_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subMonths(3)->format('d-M-Y'), 'end_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth()->format('d-M-Y')]) }}
                </p>
            </div>
            <div class="table-container">
                <p class="table-scroll-message text-grey mobile-only">
                    {{ __('messages.scroll_table') }}
                </p>
                <div class="table-wrap">
                    <table id="table-regions">
                        <colgroup>
                            <col />
                            <col span="2" />
                            <col span="2" />
                            <col span="2" />
                            <col span="2" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="sticky-col text-left">
                                    {{ __('messages.table_sub_region') }}
                                </th>
                                <th class="text-right">
                                    {{ __('messages.table_registered') }}
                                </th>
                                <th class="text-right">
                                    {{ __('messages.table_uc') }}
                                </th>
                                <th class="text-right">
                                    {{ __('messages.table_controlled') }}
                                </th>
                                <th class="text-right">
                                    {{ __('messages.table_uncontrolled') }}
                                </th>
                                <th class="text-right">
                                    {{ __('messages.table_ltfu_3mo') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($faskes) && count($faskes) > 0)
                                @foreach ($faskes as $fak)
                                    @php
                                        $patientsUnderCare = $fak->patientsUnderCare ?? 0;
                                        $registered = $patientsUnderCare + rand(0, 10); // Placeholder registered > under care
                                        $controlledPercent = 0;
                                        $uncontrolledPercent = 0;
                                        $ltfuPercent = 0;
                                    @endphp
                                    <tr>
                                        <td class="sticky-col text-left">
                                            <a href="#">{{ $fak->nama_instansi }}</a>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($registered) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($patientsUnderCare) }}
                                        </td>
                                        <td class="text-right">
                                            {{ $controlledPercent }}%
                                        </td>
                                        <td class="text-right">
                                            {{ $uncontrolledPercent }}%
                                        </td>
                                        <td class="text-right">
                                            {{ $ltfuPercent }}%
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('messages.no_data') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 3 -->
        <h2 class="columns-header spacer">3. Inventory indicators</h2>

        <div class="columns-3">
            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.dm_drug_stock') }}</h3>
                    <p>Facilities with >30 days stock</p>
                </div>
                <div class="body">
                    <p>Facilities with &ge;30 patient days protocol drugs in stock.</p>
                    <div class="figures" style="margin-top: 2em">
                        <p class="large-num">80%</p>
                        <div class="detail">
                            <p>128 facilities with >30 days stock</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.glucose_testing_avail') }}</h3>
                </div>
                <div class="body">
                    <p>Facilities with a functioning blood glucose meter</p>
                    <div class="figures" style="margin-top: 2em">
                        <p class="large-num">98%</p>
                        <div class="detail">
                            <p>157 facilities with a functioning glucose meter</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.hba1c_testing_avail') }}</h3>
                </div>
                <div class="body">
                    <p>Facilities with a functioning HbA1c machine</p>
                    <div class="figures" style="margin-top: 2em">
                        <p class="large-num" style="color:#d9534f">25%</p>
                        <div class="detail">
                            <p>40 facilities with HbA1c capacity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card spacer">
            <div class="heading">
                <h3>{{ __('messages.stock_reporting') }}</h3>
                <p>{{ __('messages.reports', ['date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M-Y')]) }}</p>
            </div>
            <div class="table-container">
                <div class="table-wrap">
                    <table id="table-stock-inventory">
                        <thead>
                            <tr>
                                <th class="sticky-col text-left">{{ __('messages.table_facility') }}</th>
                                <th class="text-right">{{ __('messages.drug_metformin') }}</th>
                                <th class="text-right">{{ __('messages.drug_glimepiride') }}</th>
                                <th class="text-right">Insulin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($faskes) && count($faskes) > 0)
                                @foreach ($faskes as $fak)
                                    <tr>
                                        <td class="sticky-col text-left"><a
                                                href="#">{{ $fak->nama_instansi }}</a></td>
                                        <td class="text-right">{{ rand(0, 100) }} days</td>
                                        <td class="text-right">{{ rand(0, 100) }} days</td>
                                        <td class="text-right">{{ rand(0, 100) }} days</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">{{ __('messages.no_data') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section 4 -->
        <h2 class="columns-header spacer">{{ __('messages.comorbidity_indicators') }}</h2>
        <div class="columns-3">
            <div class="card col-span-2">
                <div class="heading">
                    <h3>{{ __('messages.ht_dm_comorbidity') }}</h3>
                    <p>Patients enrolled in both hypertension and diabetes programs</p>
                </div>
                <div class="body">
                    <p class="large-num">25%</p>
                    <p>of diabetic patients also have hypertension</p>
                    <div class="chart" style="height: 300px">
                        <canvas id="comorbidityChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.comorbid_bp_controlled') }}</h3>
                    <p>Blood pressure control among patients with both HT and DM</p>
                </div>
                <div class="body">
                    <p class="large-num bp-controlled">45%</p>
                    <p>Blood pressure controlled</p>
                    <div class="chart" style="height: 200px">
                        <canvas id="comorbidControlChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 5 -->
        <h2 class="columns-header spacer">{{ __('messages.age_and_sex') }}</h2>
        <div class="card">
            <div class="heading">
                <h3>{{ __('messages.age_sex_diabetes') }}</h3>
            </div>
            <div class="body">
                <div class="chart" style="height: 400px">
                    <canvas id="ageSexChart"></canvas>
                </div>
            </div>
        </div>

        <div class="notes spacer">
            <aside class="notes-nav">
                <h2>Data building blocks</h2>
                <p>
                    The dashboard requires just a handful of core data building blocks
                    to generate the key indicator charts and tables.
                </p>
            </aside>
            <div class="notes-body">
                <ol>
                    <li>
                        <code>Cumulative registered patients</code>
                        <p>
                            All patients diagnosed with hypertension. Dead patients are
                            excluded.
                        </p>
                    </li>
                    <li>
                        <code>Patients under care</code>
                        <p>
                            All patients diagnosed with hypertension with a "visit" recorded
                            in the last 12 months. A visit can have a simplified definition:
                            the patient had a BP measure recorded in the last 12 months.
                            Dead patients are excluded.
                        </p>
                    </li>
                    <li>
                        <code>12 month lost to follow-up patients</code>
                        <p>
                            All patients diagnosed with hypertension with no "visit"
                            recorded in the last 12 months. A visit can have a simplified
                            definition: the patient had no BP measures recorded in the last
                            12 months. Note: Some of the key indicators exclude 12-month
                            lost to follow-up patients (i.e. the indicators only use
                            "Patients under care") because these patients become a drag on
                            the health system at scale and muddy the view of progress being
                            made improving hypertension control.
                        </p>
                    </li>
                    <li>
                        <code>Died</code>
                        <p>Dead patients are excluded from most indicators.</p>
                    </li>
                    <li>
                        <code>Patients with controlled blood pressure</code> and
                        <code>Patients with uncontrolled blood pressure</code> in a month
                        <p>
                            Controlled blood pressure in most countries is a BP measure
                            &lt;140 mmHg systolic AND &lt;90 mmHg diastolic. In a simple
                            healthcare system, you can use the most recent BP measure in a
                            month to calculate BP control. In more complex health systems
                            where multiple blood pressures are recorded at each visit, you
                            may need to average the measures.
                        </p>
                    </li>
                    <li>
                        <p><code>Screened patients</code> (Aggregate data)</p>
                    </li>
                    <li>
                        <p>
                            <code>Estimated monthly adult OPD patients</code> (Aggregate
                            data)
                        </p>
                    </li>
                </ol>
            </div>
        </div>
        <div class="notes">
            <aside class="notes-nav">
                <h2>Data definitions</h2>
                <p>
                    Many of the data definitions are based on the
                    <a href="https://apps.who.int/iris/bitstream/handle/10665/260423/WHO-NMH-NVI-18.5-eng.pdf">WHO
                        HEARTS</a>
                    technical package.
                </p>

                <span class="nav-section">Overview indicators</span>
                <a href="#note-patients-protected" class="nav-jump-links">Patients protected</a>
                <a href="#note-treatment-cascade" class="nav-jump-links">Hypertension treatment cascade</a>

                <span class="nav-section">Hypertension treatment outcomes</span>
                <a href="#note-bp-controlled" class="nav-jump-links">BP controlled at latest visit</a>
                <a href="#note-bp-uncontrolled" class="nav-jump-links">BP not controlled at latest visit</a>
                <a href="#note-bp-3-month-ltfu" class="nav-jump-links">No visit in past 3 months</a>

                <span class="nav-section">Registrations</span>
                <a href="#note-bp-under-care" class="nav-jump-links">Patients under care</a>
                <a href="#note-bp-registrations" class="nav-jump-links">Cumulative registrations</a>
                <a href="#note-bp-monthly" class="nav-jump-links">Monthly registrations</a>

                <span class="nav-section">LTFU</span>
                <a href="#note-bp-12-month-ltfu" class="nav-jump-links">12 month lost to follow-up</a>

                <span class="nav-section">Opportunistic screening</span>
                <a href="#note-bp-screened" class="nav-jump-links">% of OPD patients screened</a>
                <a href="#note-bp-screened-monthly" class="nav-jump-links">Monthly OPD patients screened</a>

                <span class="nav-section">Cohorts</span>
                <a href="#note-bp-cohorts" class="nav-jump-links">Cohort reports</a>

                <span class="nav-section">Overdue</span>
                <a href="#note-bp-overdue" class="nav-jump-links">Overdue patients</a>
                <a href="#note-bp-overdue-called" class="nav-jump-links">Overdue patients called</a>
                <a href="#note-bp-overdue-called-returned" class="nav-jump-links">Overdue that returned to care</a>

                <span class="nav-section">Stock</span>
                <a href="#note-bp-drug-stock" class="nav-jump-links">Anti-hypertensive drug stock</a>
                <a href="#note-bp-monitors" class="nav-jump-links">Blood pressure monitors</a>
            </aside>
            <div class="notes-body">
                <div class="notes-section">
                    <div class="notes-note">
                        <h5>Overview indicators</h5>
                        <p>
                            The overview indicators are OPTIONAL indicators that are useful
                            as your program scales. They can be used both for program
                            management and can be useful data for advocacy.
                        </p>
                    </div>
                    <div class="notes-details"></div>
                </div>
                <div class="notes-section">
                    <div class="notes-note" id="note-patients-protected">
                        <h6>Patients protected</h6>
                        <p>
                            This indicator is a very simplistic representation of the number
                            of patients controlled. It is primarily useful as a motivational
                            tool for health staff and for advocacy. The data simply
                            represents the numerator from the "BP controlled at latest visit
                            in past 3 months" indicator below.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Value</h6>
                            <p>
                                <code>Number of patients with controlled blood pressure</code>
                                (&lt;140 systolic AND &lt;90 diastolic) recorded at the
                                patient's most recent visit within the past 3 months.
                            </p>
                            <h6>Example</h6>
                            <p>
                                By the end of July, 4808 patients had a visit anytime from May
                                1 to July 31 and at their most recent visit, they had their BP
                                controlled.
                            </p>
                        </blockquote>
                    </div>
                </div>
                <div class="notes-section">
                    <div class="notes-note" id="note-treatment-cascade">
                        <h6>Hypertension treatment cascade</h6>
                        <p>
                            This indicator gives a view of how many expected individuals in
                            the region are under treatment for hypertension and, of those
                            patients, how many have their blood pressure under control.
                        </p>
                        <p>
                            <b>Note:</b> The "Estimated number of adults" is usually derived
                            from community surveys (e.g.
                            <a
                                href="https://www.who.int/teams/noncommunicable-diseases/surveillance/systems-tools/steps">WHO
                                STEPS survey</a>) and can be updated annually or even less frequently in a data
                            system.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerators</h6>
                            <p>
                                The numerators for
                                <code>Cumulative registered patients</code>,
                                <code>Patients under care</code>, and
                                <code>Patients with BP controlled</code> are simply the values
                                from the charts lower in the report.
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                Number of people in the region estimated to have hypertension.
                                (This is commonly derived from community survey data e.g.
                                STEPS survey)
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note">
                        <h5>Hypertension treatment outcomes</h5>
                        <p>
                            The 3 treatment outcome indicators show whether patients in the
                            hypertension control program are visiting health facilities
                            regularly. Blood pressure control is the best indicator to know
                            if patients under treatment are being treated effectively.
                        </p>
                        <p>All 3 indicators have the same denominator.</p>
                    </div>
                    <div class="notes-details">
                        <!--blockquote>
                    <h6>Denominator</h6>
                    <p><code>Patients under care</code> in the hypertension program <i>registered before the past 3 months</i>. Exclusions: <code>Dead</code> and <code>12 month lost to follow-up</code> patients are excluded from the denominator.</p>
                  </blockquote-->
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-controlled">
                        <h6>BP controlled at latest visit in past 3 months</h6>
                        <p>
                            How many patients enrolled in the hypertension control program
                            visited a health facility in the past 3 months with a healthy
                            blood pressure?
                        </p>
                        <p>
                            <b>Why are patients registered within the past 3 months
                                excluded?</b>
                            Three months gives patients time to take their hypertension
                            medication and to get their BP controlled. Most newly registered
                            patients have uncontrolled blood pressure and including them
                            would not reflect an accurate picture of actual controlled
                            patients.
                        </p>
                        <p>
                            <b>Why is this important?</b> BP controlled reflects the overall
                            health of a hypertension control program and is the most
                            important hypertension control indicator.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                <code>Patients with controlled blood pressure</code> (&lt;140
                                systolic AND &lt;90 diastolic) recorded at the patient's most
                                recent visit within the past 3 months.
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                <code>Patients under care</code> in the hypertension program
                                <i>registered before the past 3 months</i>. Exclusions:
                                <code>Dead</code> and
                                <code>12 month lost to follow-up</code> patients are excluded
                                from the denominator.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-uncontrolled">
                        <h6>BP not controlled at latest visit in past 3 months</h6>
                        <p>
                            How many patients enrolled in the hypertension control program
                            visited a health facility in the past 3 months with high blood
                            pressure?
                        </p>
                        <p>
                            <b>Why is this important?</b> BP not controlled shows which
                            patients are coming back to care, but require continued
                            intervention to control their blood pressure.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                <code>Patients with uncontrolled blood pressure</code>
                                (&ge;140 systolic OR &ge;90 diastolic) recorded at the
                                patient's most recent visit within the past 3 months.
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                <code>Patients under care</code> in the hypertension program
                                <i>registered before the past 3 months</i>. Exclusions:
                                <code>Dead</code> and
                                <code>12 month lost to follow-up</code> patients are excluded
                                from the denominator.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-3-month-ltfu">
                        <h6>No visit in 3 months</h6>
                        <p>
                            How many patients enrolled in the hypertension control program
                            did not visit a health facility in the past 3 months?
                        </p>
                        <p>
                            <b>Note:</b> This can also be called "3 month lost to
                            follow-up."
                        </p>
                        <p>
                            <b>Why is this important?</b> This number reflects how good
                            facilities are at retaining patients in care.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>Patients no BP measure recorded within the past 3 months.</p>
                            <h6>Denominator</h6>
                            <p>
                                <code>Patients under care</code> in the hypertension program
                                <i>registered before the past 3 months</i>. Exclusions:
                                <code>Dead</code> and
                                <code>12 month lost to follow-up</code> patients are excluded
                                from the denominator.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-registrations">
                        <h5>Registrations</h5>
                        <p>
                            It is useful to know how many patients are enrolled in a
                            hypertension control program and how many of those patients are
                            "under care" (i.e. have visited at least once in the past 12
                            months).
                        </p>
                    </div>
                    <div class="notes-details"></div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-under-care">
                        <h6>Patients under care</h6>
                        <p>
                            Track the patients who have visited in the last 12 months and
                            can be considered to be under care in the hypertension control
                            program.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Value</h6>
                            <code>Cumulative registrations</code> in the hypertension
                            program excluding <code>12 month lost to follow-up</code> and
                            <code>Dead</code> patients.
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-registrations">
                        <h6>Cumulative registrations</h6>
                        <p>
                            Track all of the patients who have been registered in the
                            hypertension program. Over time, the cumulative registrations
                            will be a larger number than <code>Patients under care</code>.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Value</h6>
                            <code>Cumulative registrations</code> in the hypertension
                            program excluding <code>Dead</code> patients.
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-monthly">
                        <h6>Monthly new patients registered</h6>
                        <p>
                            Monthly registrations give visibility into activity
                            on-the-ground by health facility staff.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Value</h6>
                            <i>New patients registered</i> in the hypertension program in a
                            month who are diagnosed with hypertension (diagnosis could have
                            happened on any date).
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-12-month-ltfu">
                        <h5>12 month lost to follow-up patients</h5>
                        <p>
                            Patients with no "visit" in the past 12 months (i.e. no BP
                            measure recorded in the past 12 months).
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                Patients no BP measure recorded within the past 12 months.
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                <code>Cumulative registered patients</code> in the
                                hypertension program. Exclusions: <code>Dead</code> patients
                                are excluded from the denominator.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-percent-registered">
                        <h6>% of people registered</h6>
                        <p>
                            The number of patients in the region registered in the
                            hypertension control program.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p><code>Cumulative registrations</code></p>
                            <h6>Denominator</h6>
                            <p>
                                <code>Estimated number of adults with hypertension</code> in
                                the community.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" note-bp-percent-under-care>
                        <h6>% of people under care</h6>
                        <p>
                            The number of patients in the region registered in the
                            hypertension control program who have visited a facility at
                            least once in the past 12 months.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p><code>Patients under care</code></p>
                            <h6>Denominator</h6>
                            <p>
                                <code>Estimated number of adults with hypertension</code> in
                                the community.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-percent-controlled">
                        <h6>% of people with BP controlled</h6>
                        <p>
                            The number of patients in the region registered in the
                            hypertension control program who have their BP controlled in the
                            past 3 months.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                <code>Patients with controlled blood pressure</code> in past 3
                                months.
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                <code>Estimated number of adults with hypertension</code> in
                                the community.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="bp-screening">
                        <h5>Opportunistic screening</h5>
                        <p>
                            Facility-based opportunistic screening gives visibility into how
                            many patients in the OPD (Outpatient Department) of hospitals
                            are having their blood pressure measured to screen for
                            hypertension.
                        </p>
                        <p>
                            <b>Note:</b> In many health systems, the number of screened
                            patients and the approximate number of adult patients visiting
                            the OPD are only recorded on paper and are reported monthly in
                            aggregate.
                        </p>
                    </div>
                    <div class="notes-details"></div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-screened">
                        <h6>% of OPD patients screened for hypertension</h6>
                        <p>
                            The approximate percentage of patients screened for hypertension
                            in a month.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                <i>Adult OPD patients</i> with a blood pressure measure taken
                                in a month
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                Approximate <i>Adult patients visiting the OPD</i> of
                                facilities per month
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-screened-monthly">
                        <h6>Monthly OPD patients screened for hypertension</h6>
                        <p>
                            The number of patients screened for hypertension in a month.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Value</h6>
                            <p>
                                <i>Adult OPD patients</i> with a blood pressure measure taken
                                in a month
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="bp-screening">
                        <h5>Overdue patients</h5>
                        <p>
                            Getting patients to attend for regular care is a major challenge
                            for hypertension control programs.
                        </p>
                        <p>
                            Overdue patients reports provide facility staff visibility on
                            how many patients did not attend their scheduled visit and the
                            effectiveness of patient outreach systems.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Overdue patient</h6>
                            <p>
                                A patient with a scheduled visit date that has passed with no
                                visit.
                            </p>
                        </blockquote>
                    </div>
                </div>
                <div class="notes-section">
                    <div class="notes-note" id="note-bp-overdue">
                        <h6>Overdue patients</h6>
                        <p>
                            The percentage of patients that are overdue on the first of the
                            month.
                        </p>
                        <p>
                            <b>Note:</b> This number is set on the 1st of the month does not
                            change during the month.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                The number of <i>Overdue patients</i> on the 1st of the month,
                                patients that were previously called and marked 'Remove from
                                overdue list' and patients with no phone number are excluded.
                            </p>

                            <h6>Denominator</h6>
                            <p>
                                <i>Patients under care</i> excluding dead patients and 12
                                month lost to follow-up patients.
                            </p>
                        </blockquote>
                    </div>
                </div>
                <div class="notes-section">
                    <div class="notes-note" id="note-bp-overdue-called">
                        <h6>Overdue patients called</h6>
                        <p>
                            The percentage of overdue patients that were called during the
                            month.
                        </p>
                        <p>
                            <b>Note:</b> The numerator is not a subset of the denominator
                            and as a result may exceed 100%. The value shown is capped at
                            100%.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                The number of unique <i>Overdue patients called</i> during the
                                month.
                            </p>
                            <p>
                                <b>Note:</b> This number includes patients that became overdue
                                during the month and were called, these patients may not be
                                counted in the denominator.
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                The number of <i>Overdue patients</i> on the 1st of the month,
                                patients that were previously called and marked 'Remove from
                                overdue list' and patients with no phone number are excluded.
                            </p>
                        </blockquote>
                    </div>
                </div>
                <div class="notes-section">
                    <div class="notes-note" id="note-bp-overdue-called-returned">
                        <h6>Overdue patients called that returned to care</h6>
                        <p>
                            The percentage of overdue patients that were called during the
                            month and returned to care within 15 days.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>
                                The number of overdue patients that returned to care within 15
                                days of their first call.
                            </p>
                            <p>
                                <b>Note:</b> For patients called multiple times during the
                                month, 15 days begins from the date of the first call during
                                the month.
                            </p>
                            <h6>Denominator</h6>
                            <p>
                                The number of unique <i>Overdue patients called</i> during the
                                month.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-cohorts">
                        <h5>Cohort reports</h5>
                        <p>
                            Cohorts allow program managers to track a set of patients
                            receiving treatment over time. The dashboard takes all the
                            patients registered during a quarter and displays the outcome of
                            their visit in the following quarter.
                        </p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>BP controlled numerator</h6>
                            <p>
                                The number of <i>Patients with a BP &lt;140 AND &lt;90</i> at
                                their last visit in the quarter after the quarter when they
                                were registered.
                            </p>

                            <h6>BP not controlled numerator</h6>
                            <p>
                                The number of <i>Patients with a BP &ge;140 OR &ge;90</i> at
                                their last visit in the quarter after the quarter when they
                                were registered.
                            </p>

                            <h6>No visit in 3 months numerator</h6>
                            <p>
                                The number of <i>Patients with no visit</i> in the quarter
                                after the quarter when they were registered.
                            </p>

                            <h6>Denominator</h6>
                            <p>
                                <i>New patients registered</i> in the hypertension program in
                                a quarter.
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="">
                        <h5>Drug stock and BP monitor inventory</h5>
                        <p>Description...</p>
                    </div>
                    <div class="notes-details"></div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-drug-stock">
                        <h6>Anti-hypertensive drug stock</h6>
                        <p>Definition...</p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Patient days calculation</h6>
                            <p>
                                <i>Quantity of DRUG ABC 5 mg</i> +<br />
                                <i>Quantity of DRUG ABC 10 mg</i> * 2<br />
                                / <code>Patients under care</code> *
                                <i>Estimated % of patients on DRUG ABC</i><br />
                                = <i>Patient days of drug stock</i>
                            </p>
                        </blockquote>
                        <blockquote>
                            <h6>Example</h6>
                            <p style="font-family: monospace">
                                &nbsp;&nbsp;<i>25,000 pills of Amlodipine 5 mg</i> +<br />
                                &nbsp;&nbsp;<i>15,000 pills of Amlodipine 10 mg</i> * 2<br />
                                / <code>6,000 patients under care</code> * <i>72%</i><br />
                                = <i>12.5 patient days of Amlodipine</i>
                            </p>
                        </blockquote>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-note" id="note-bp-monitors">
                        <h6>Blood pressure monitors</h6>
                        <p>Definition...</p>
                    </div>
                    <div class="notes-details">
                        <blockquote>
                            <h6>Numerator</h6>
                            <p>Facilities with <i>&ge;1 blood pressure monitor</i></p>
                            <h6>Denominator</h6>
                            <p>
                                <i>All facilities</i> in the hypertension control program.
                            </p>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/hearts360/charts.js') }}"></script>

    <!-- Tablesort.js -->
    <script src="{{ asset('js/hearts360/tablesort.js') }}"></script>
    <script src="{{ asset('js/hearts360/tablesort.number.js') }}"></script>
    <script src="{{ asset('js/hearts360/navigation.js') }}?v=1.1"></script>
    <script>
        new Tablesort(document.getElementById("table-regions"));
        new Tablesort(document.getElementById("table-stock-inventory"));
        document.addEventListener('DOMContentLoaded', function() {
            // Update Charts with Real Data from Controller
            setTimeout(() => {
                const labels = @json($chartLabels);

                // Blood sugar Controlled
                const bpChart = Chart.getChart("bpcontrolled");
                if (bpChart && labels.length > 0) {
                    bpChart.data.labels = labels;
                    bpChart.data.datasets[0].data = @json($chartValues);
                    bpChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels.length);
                    bpChart.update();
                }

                // Blood sugar Uncontrolled
                const bpUncontrolledChart = Chart.getChart("bpuncontrolled");
                if (bpUncontrolledChart && labels.length > 0) {
                    bpUncontrolledChart.data.labels = labels;
                    bpUncontrolledChart.data.datasets[0].data = @json($uncontrolledValues);
                    bpUncontrolledChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels.length);
                    bpUncontrolledChart.update();
                }

                // No visit in 3 months
                const ltfu3mChart = Chart.getChart("ltfu3Month");
                if (ltfu3mChart && labels.length > 0) {
                    ltfu3mChart.data.labels = labels;
                    ltfu3mChart.data.datasets[0].data = @json($ltfu3mValues);
                    ltfu3mChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels.length);
                    ltfu3mChart.update();
                }

                // Patients Under Care (index 1 in registrations chart)
                const regChart = Chart.getChart("registrations");
                if (regChart && labels.length > 0) {
                    regChart.data.labels = labels;
                    regChart.data.datasets[1].data = @json($pucValues);
                    regChart.update();
                }

                // 12 month LTFU
                const ltfuChart = Chart.getChart("ltfu12Months");
                if (ltfuChart && labels.length > 0) {
                    ltfuChart.data.labels = labels;
                    ltfuChart.data.datasets[0].data = @json($ltfuValues);
                    ltfuChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels.length);
                    ltfuChart.update();
                }

                // Screening
                const screenChart = Chart.getChart("screenings");
                if (screenChart && labels.length > 0) {
                    screenChart.data.labels = labels;
                    screenChart.data.datasets[0].data = @json($screenValues);
                    screenChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels.length);
                    screenChart.update();
                }
            }, 500);

            new Tablesort(document.getElementById("table-regions"));
            new Tablesort(document.getElementById("table-stock-inventory"));

            // Event delegation for toggling regions
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('toggle-region')) {
                    const caret = e.target;
                    const parentLi = caret.parentElement;
                    const nestedUl = parentLi.querySelector('.nested');

                    // Toggle styles immediately
                    caret.classList.toggle('caret-down');
                    nestedUl.classList.toggle('active');

                    // If already loaded, do nothing else
                    if (caret.dataset.loaded === 'true') {
                        return;
                    }

                    // Fetch data
                    const type = caret.dataset.type;
                    const parentId = caret.dataset.parentId;
                    let url = '';
                    let nextType = '';

                    if (type === 'regency') {
                        url = '{{ route('hearts360.get-regencies') }}?province_id=' + parentId;
                        nextType = 'district';
                    } else if (type === 'district') {
                        url = '{{ route('hearts360.get-districts') }}?regency_id=' + parentId;
                        nextType = 'village';
                    } else if (type === 'village') {
                        url = '{{ route('hearts360.get-villages') }}?district_id=' + parentId;
                        nextType = 'faskes';
                    } else if (type === 'faskes') {
                        url = '{{ route('hearts360.get-faskes') }}?village_id=' + parentId;
                        nextType = 'none';
                    }

                    if (url) {
                        // Show loading or something? caret is already toggled
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    let html = '';
                                    data.forEach(item => {
                                        if (nextType === 'faskes') {
                                            // Next is faskes (village level)
                                            html += `<li class="region-village">
                                              <span class="caret toggle-region" data-type="faskes" data-parent-id="${item.id}"></span>
                                              <a href="#">${item.name}</a>
                                              <ul class="nested"></ul>
                                          </li>`;
                                        } else if (type === 'faskes') {
                                            // Currently displaying faskes
                                            html += `<li class="region-facility">
                                              <a href="#">${item.nama_instansi}</a>
                                            </li>`;
                                        } else if (type === 'regency') {
                                            // Displaying regencies, next is district
                                            // Redirect to diabetes with regency_id parameter when clicked
                                            html += `<li class="region-district">
                                              <span class="caret toggle-region" data-type="district" data-parent-id="${item.id}"></span>
                                              <a href="{{ route('hearts360.diabetes') }}?regency_id=${item.id}">${item.name}</a>
                                              <ul class="nested"></ul>
                                          </li>`;
                                        } else if (type === 'district') {
                                            // Displaying districts, next is village
                                            // Redirect to diabetes with district_id parameter when clicked
                                            html += `<li class="region-subdistrict">
                                              <span class="caret toggle-region" data-type="village" data-parent-id="${item.id}"></span>
                                              <a href="{{ route('hearts360.diabetes') }}?district_id=${item.id}">${item.name}</a>
                                              <ul class="nested"></ul>
                                          </li>`;
                                        }
                                    });
                                    nestedUl.innerHTML = html;
                                    caret.dataset.loaded = 'true';
                                } else {
                                    nestedUl.innerHTML = '<li><a href="#">Tidak ada data</a></li>';
                                    caret.dataset.loaded = 'true';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                                nestedUl.innerHTML = '<li><a href="#">Error loading data</a></li>';
                            });
                    }
                }
            });
            });

            // Filter helpers
            function updateFilter(param, value) {
                const url = new URL(window.location.href);
                if (value) {
                    url.searchParams.set(param, value);
                    if (param === 'province_id') {
                        url.searchParams.delete('regency_id');
                        url.searchParams.delete('district_id');
                    } else if (param === 'regency_id') {
                        url.searchParams.delete('district_id');
                    }
                } else {
                    url.searchParams.delete(param);
                    if (param === 'province_id') {
                        url.searchParams.delete('regency_id');
                        url.searchParams.delete('district_id');
                    } else if (param === 'regency_id') {
                        url.searchParams.delete('district_id');
                    }
                }
                window.location.href = url.toString();
            }

            const provinceSelect = document.getElementById('province-select');
            if (provinceSelect) {
                provinceSelect.addEventListener('change', function() {
                    updateFilter('province_id', this.value);
                });
            }

            const regencySelect = document.getElementById('regency-select');
            if (regencySelect) {
                regencySelect.addEventListener('change', function() {
                    updateFilter('regency_id', this.value);
                });
            }

            const yearSelect = document.getElementById('year-select');
            if (yearSelect) {
                yearSelect.addEventListener('change', function() {
                    updateFilter('year', this.value);
                });
            }

            const monthSelect = document.getElementById('month-select');
            if (monthSelect) {
                monthSelect.addEventListener('change', function() {
                    updateFilter('month', this.value);
                });
            }
        });
    </script>
</body>

</html>
