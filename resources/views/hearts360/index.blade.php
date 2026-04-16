<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ __('messages.title') }}</title>
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
            <li class="active-link">
                <a href="{{ route('hearts360.hipertensi', request()->query()) }}">
                    {{ __('messages.hypertension') }} <span class="desktop-only">{{ __('messages.dashboard') }}</span>
                </a>
            </li>
            <li>
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

        <h2 class="columns-header">{{ __('messages.overview_indicators') }}</h2>

        <div class="columns-3">
            <div class="card col-span-2">
                <div class="heading">
                    <h3>
                        {{ __('messages.patients_protected') }}
                    </h3>
                    <div class="figures">
                        <p class="large-num bp-controlled">{{ number_format($latestControlCount) }} patients</p>
                        <div class="detail">
                            <p>{{ __('messages.patients_with_bp_ctrl', ['count' => number_format($latestControlCount)]) }}</p>
                        </div>
                    </div>
                    <div class="chart" style="height: 350px">
                        <canvas id="patientsprotected"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.ht_treatment_cascade') }}</h3>
                    <p>
                        {{ __('messages.cascade_desc', ['location' => $selectedLocationName]) }}
                    </p>
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <b>{{ __('messages.est_pop_bold') }}</b> {{ __('messages.est_pop_info') }}
                            </p>
                            <p>
                                <b>{{ __('messages.puc_bold') }}</b> {{ __('messages.puc_info') }}
                            </p>
                        </div>
                    </div>
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
                                {{ __('messages.est_people_ht') }}
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
                            <p class="text-grey label-small">{{ __('messages.patients_bp_ctrl') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="columns-header spacer">{{ __('messages.prog_mgmt_indicators') }}</h2>

        <div class="card">
            <div class="heading">
                <h3>{{ __('messages.ht_outcomes') }}</h3>
                <div class="info">
                    <div class="info-hover-text">
                        <p>
                            <span>{{ __('messages.num_bp_ctrl') }}</span> {{ __('messages.num_bp_ctrl_desc') }}
                        </p>
                        <p>
                            <span>{{ __('messages.num_bp_unctrl') }}</span> {{ __('messages.num_bp_unctrl_desc') }}
                        </p>
                        <p>
                            <span>{{ __('messages.num_no_visit') }}</span> {{ __('messages.num_no_visit_desc') }}
                        </p>
                        <p>
                            <span>{{ __('messages.denominator') }}:</span> {{ __('messages.denom_desc') }}
                        </p>
                    </div>
                </div>
                <p>
                    {{ __('messages.outcomes_desc', [
                        'count' => number_format($latestControlCount ?? 0), 
                        'date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('d-M-Y'), 
                        'start_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subMonths(3)->format('d-M-Y'), 
                        'end_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth()->format('d-M-Y')
                    ]) }}
                </p>

                <div class="body columns-3">
                    <div class="inner-card">
                        <div class="figures">
                            <h4 class="bp-controlled">
                                {{ __('messages.bp_ctrl_latest') }}
                            </h4>
                            <p class="large-num bp-controlled">{{ $latestControlRate ?? 0 }}%</p>
                            <div class="detail">
                                <p>{{ __('messages.patients_with_bp_ctrl', ['count' => number_format($latestControlCount ?? 0)]) }}
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
                                {{ __('messages.bp_unctrl_latest') }}
                            </h4>
                            <p class="large-num bp-uncontrolled">{{ $latestUncontrolledRate ?? 0 }}%</p>
                            <div class="detail">
                                <p>{{ __('messages.patients_with_bp_unctrl', ['count' => number_format($latestUncontrolledCount ?? 0)]) }}</p>
                            </div>
                        </div>
                        <div class="chart">
                            <canvas id="bpuncontrolled"></canvas>
                        </div>
                    </div>
                    <div class="inner-card">
                        <div class="figures">
                            <h4 class="three-month-ltfu">{{ __('messages.no_visit_3mo') }}</h4>
                            <p class="large-num three-month-ltfu">{{ $latestLtfu3mRate ?? 0 }}%</p>
                            <div class="detail">
                                <p>{{ __('messages.patients_with_no_visit', ['count' => number_format($latestLtfu3mCount ?? 0)]) }}</p>
                            </div>
                        </div>
                        <div class="chart">
                            <canvas id="ltfu3Month"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns-3">
            <div class="card">
                <div class="heading">
                    <h3>{{ __('messages.puc_bold') }}</h3>
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>{{ __('messages.puc_bold') }}:</span> {{ __('messages.puc_tooltip') }}
                            </p>
                            <p>
                                <span>{{ __('messages.cumulative_reg') }}:</span> {{ __('messages.cum_reg_tooltip') }}
                            </p>
                            <p>
                                <span>{{ __('messages.nav_monthly_reg') }}:</span>
                                {{ __('messages.monthly_reg_tooltip') }}
                            </p>
                        </div>
                    </div>
                    <p>
                        {{ __('messages.puc_subtext') }}
                    </p>
                </div>
                <div class="body">
                    <div class="figures">
                        <div>
                            <p class="large-num under-care">{{ number_format($latestPucCount ?? 0) }}</p>
                            <div class="detail">
                                <p>{{ __('messages.patients_reg_in', ['count' => number_format($latestMonthlyNew ?? 0), 'date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M-Y')]) }}</p>
                                <p class="text-grey">
                                    <span
                                        class="registrations">{{ __('messages.of_cumulative_reg', ['count' => number_format($latestCumRegCount ?? 0)]) }}</span>
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
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>{{ __('messages.numerator') }}:</span> {{ __('messages.ltfu_num_desc') }}
                            </p>
                            <p>
                                <span>{{ __('messages.denominator') }}:</span> {{ __('messages.ltfu_denom_desc') }}
                            </p>
                        </div>
                    </div>
                    <p>
                        {{ __('messages.ltfu_card_text') }}
                    </p>
                </div>
                <div class="body">
                    <div class="figures">
                        <p class="large-num twelve-month-ltfu">{{ $latestLtfu12mRate ?? 0 }}%</p>
                        <div class="detail">
                            <p>{{ __('messages.ltfu_chart_text', ['count' => number_format($latestLtfu12mCount ?? 0), 'start_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subYear()->format('M-Y'), 'end_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M-Y')]) }}
                            </p>
                            <p class="text-grey">
                                <span
                                    class="registrations">{{ __('messages.of_cumulative_reg', ['count' => number_format($latestCumRegCount ?? 0)]) }}</span>
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
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>{{ __('messages.numerator') }}:</span> {{ __('messages.scr_num_desc') }}
                            </p>
                            <p>
                                <span>{{ __('messages.denominator') }}:</span> {{ __('messages.scr_denom_desc') }}
                            </p>
                        </div>
                    </div>
                    <p>{{ __('messages.scr_card_text') }}</p>
                </div>
                <div class="body">
                    <div class="figures">
                        <div>
                            <p class="large-num" style="color: #34aea0">{{ $latestScreenRate ?? 0 }}%</p>
                            <div class="detail">
                                <p>{{ __('messages.scr_chart_text', ['count' => number_format($latestScreenCount ?? 0), 'date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M-Y')]) }}</p>
                                <p class="text-grey">
                                    {{ __('messages.scr_est_text', ['count' => number_format($latestScreenDenominator ?? 0)]) }}
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

        <div class="card">
            <div class="heading">
                <h3>{{ __('messages.compare_sub_regions') }}</h3>
                <p>
                    {{ __('messages.compare_desc', ['start_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->subMonths(3)->format('d-M-Y'), 'end_date' => \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth()->format('d-M-Y')]) }}
                </p>
            </div>
            <div class="table-container">
                <p class="table-scroll-message text-grey mobile-only">
                    {{ __('messages.scroll_table') }}
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
                            <tr class="text-center">
                                <td></td>
                                <th scope="colgroup">{{ __('messages.col_puc') }}</th>
                                <th scope="colgroup">{{ __('messages.col_new_reg') }}</th>
                                <th colspan="2" scope="colgroup">{{ __('messages.col_bp_ctrl') }}</th>
                                <th colspan="2" scope="colgroup">{{ __('messages.col_bp_unctrl') }}</th>
                                <th colspan="2" scope="colgroup">
                                    {{ __('messages.col_no_visit') }}
                                </th>
                            </tr>
                            <tr class="head-bg">
                                <th>
                                    <div>{{ __('messages.col_sub_regions') }}</div>
                                </th>
                                <th class="text-right">
                                    <div>{{ __('messages.total') }}</div>
                                </th>
                                <th class="text-right">
                                    <div>{{ \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('M-Y') }}</div>
                                </th>
                                <th class="text-right">
                                    <div>{{ __('messages.total') }}</div>
                                </th>
                                <th data-sort-default>
                                    <div>{{ __('messages.percent') }}</div>
                                </th>
                                <th class="text-right">
                                    <div>{{ __('messages.total') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('messages.percent') }}</div>
                                </th>
                                <th class="text-right">
                                    <div>{{ __('messages.total') }}</div>
                                </th>
                                <th>
                                    <div>{{ __('messages.percent') }}</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($faskes) && $faskes->count() > 0)
                                @php
                                    $totalPatientsUnderCare = 0;
                                    $totalMonthlyNew = 0;
                                    $totalBPControlled = 0;
                                    $totalBPUncontrolled = 0;
                                    $totalNoVisit = 0;
                                @endphp
                                @foreach ($faskes as $fask)
                                    @php
                                        $patientsUnderCare = $fask->patientsUnderCare ?? 0;
                                        $monthlyNew = $fask->monthlyNew ?? 0;
                                        $bpControlled = $fask->bpControlled ?? 0;
                                        $bpUncontrolled = $fask->bpUncontrolled ?? 0;
                                        $noVisit = $fask->noVisit ?? 0;
                                        $bpControlledPercent = 0;
                                        $bpUncontrolledPercent = 0;
                                        $noVisitPercent = 0;

                                        if ($patientsUnderCare > 0) {
                                            $bpControlledPercent = round(($bpControlled / $patientsUnderCare) * 100);
                                            $bpUncontrolledPercent = round(($bpUncontrolled / $patientsUnderCare) * 100);
                                            $noVisitPercent = round(($noVisit / $patientsUnderCare) * 100);
                                        }

                                        $totalPatientsUnderCare += $patientsUnderCare;
                                        $totalMonthlyNew += $monthlyNew;
                                        $totalBPControlled += $bpControlled;
                                        $totalBPUncontrolled += $bpUncontrolled;
                                        $totalNoVisit += $noVisit;
                                    @endphp
                                    <tr>
                                        <th class="link"><a href="#">{{ $fask->nama_instansi }}</a></th>
                                        <td class="number under-care bold">{{ number_format($patientsUnderCare) }}
                                        </td>
                                        <td class="number">{{ number_format($monthlyNew) }}</td>
                                        <td class="number">{{ number_format($bpControlled) }}</td>
                                        <td class="bp-controlled bold">{{ $bpControlledPercent }}%</td>
                                        <td class="number">{{ number_format($bpUncontrolled) }}</td>
                                        <td class="bp-uncontrolled bold">{{ $bpUncontrolledPercent }}%</td>
                                        <td class="number">{{ number_format($noVisit) }}</td>
                                        <td class="three-month-ltfu bold">{{ $noVisitPercent }}%</td>
                                    </tr>
                                @endforeach
                                @php
                                    $totalBPControlledPercent =
                                        $totalPatientsUnderCare > 0
                                            ? round(($totalBPControlled / $totalPatientsUnderCare) * 100)
                                            : 0;
                                    $totalBPUncontrolledPercent =
                                        $totalPatientsUnderCare > 0
                                            ? round(($totalBPUncontrolled / $totalPatientsUnderCare) * 100)
                                            : 0;
                                    $totalNoVisitPercent =
                                        $totalPatientsUnderCare > 0
                                            ? round(($totalNoVisit / $totalPatientsUnderCare) * 100)
                                            : 0;
                                @endphp
                                <tr class="totals" data-sort-method="none">
                                    <th class="link"><b>{{ __('messages.total') }}</b></th>
                                    <td class="number under-care bold">{{ number_format($totalPatientsUnderCare) }}
                                    </td>
                                    <td class="number">{{ number_format($totalMonthlyNew) }}</td>
                                    <td class="number">{{ number_format($totalBPControlled) }}</td>
                                    <td class="bp-controlled bold">{{ $totalBPControlledPercent }}%</td>
                                    <td class="number">{{ number_format($totalBPUncontrolled) }}</td>
                                    <td class="bp-uncontrolled bold">{{ $totalBPUncontrolledPercent }}%</td>
                                    <td class="number">{{ number_format($totalNoVisit) }}</td>
                                    <td class="three-month-ltfu bold">{{ $totalNoVisitPercent }}%</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('messages.no_data_district') }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="heading">
                <h3>{{ __('messages.q_cohort_reports') }}</h3>
                <p>
                    {{ __('messages.q_cohort_desc') }}
                </p>
            </div>
            <div class="body">
                <p class="table-scroll-message text-grey mobile-only">
                    {{ __('messages.scroll_chart') }}
                </p>
                <div class="table-wrap">
                    <div class="cohort">
                        <div class="cohort-quarter">
                            <div class="cohort-bar">
                                <div class="segment bp-controlled-bg" style="height: 63%">
                                    63%
                                </div>
                                <div class="segment bp-uncontrolled-bg" style="height: 18%">
                                    18%
                                </div>
                                <div class="segment three-month-ltfu-bg" style="height: 21%">
                                    21%
                                </div>
                            </div>
                            <div class="cohort-detail">
                                <b>{{ __('messages.cohort_name', ['quarter' => 'Q4-2022']) }}</b>
                                {{ __('messages.patients_count', ['count' => '1,006']) }}
                                <p class="text-grey">{{ __('messages.cohort_result', ['quarter' => 'Q1-2023']) }}</p>
                            </div>
                        </div>
                        <div class="cohort-quarter">
                            <div class="cohort-bar">
                                <div class="segment bp-controlled-bg" style="height: 66%">
                                    66%
                                </div>
                                <div class="segment bp-uncontrolled-bg" style="height: 15%">
                                    15%
                                </div>
                                <div class="segment three-month-ltfu-bg" style="height: 21%">
                                    21%
                                </div>
                            </div>
                            <div class="cohort-detail">
                                <b>{{ __('messages.cohort_name', ['quarter' => 'Q1-2023']) }}</b>
                                {{ __('messages.patients_count', ['count' => '600']) }}
                                <p class="text-grey">{{ __('messages.cohort_result', ['quarter' => 'Q2-2023']) }}</p>
                            </div>
                        </div>
                        <div class="cohort-quarter">
                            <div class="cohort-bar">
                                <div class="segment bp-controlled-bg" style="height: 60%">
                                    60%
                                </div>
                                <div class="segment bp-uncontrolled-bg" style="height: 19%">
                                    19%
                                </div>
                                <div class="segment three-month-ltfu-bg" style="height: 21%">
                                    21%
                                </div>
                            </div>
                            <div class="cohort-detail">
                                <b>{{ __('messages.cohort_name', ['quarter' => 'Q2-2023']) }}</b>
                                {{ __('messages.patients_count', ['count' => '892']) }}
                                <p class="text-grey">{{ __('messages.cohort_result', ['quarter' => 'Q3-2023']) }}</p>
                            </div>
                        </div>
                        <div class="cohort-quarter">
                            <div class="cohort-bar">
                                <div class="segment bp-controlled-bg" style="height: 68%">
                                    68%
                                </div>
                                <div class="segment bp-uncontrolled-bg" style="height: 16%">
                                    16%
                                </div>
                                <div class="segment three-month-ltfu-bg" style="height: 22%">
                                    22%
                                </div>
                            </div>
                            <div class="cohort-detail">
                                <b>{{ __('messages.cohort_name', ['quarter' => 'Q3-2023']) }}</b>
                                {{ __('messages.patients_count', ['count' => '1,315']) }}
                                <p class="text-grey">{{ __('messages.cohort_result', ['quarter' => 'Q4-2023']) }}</p>
                            </div>
                        </div>
                        <div class="cohort-quarter">
                            <div class="cohort-bar">
                                <div class="segment bp-controlled-bg" style="height: 76%">
                                    76%
                                </div>
                                <div class="segment bp-uncontrolled-bg" style="height: 10%">
                                    10%
                                </div>
                                <div class="segment three-month-ltfu-bg" style="height: 14%">
                                    14%
                                </div>
                            </div>
                            <div class="cohort-detail">
                                <b>{{ __('messages.cohort_name', ['quarter' => 'Q4-2023']) }}</b>
                                {{ __('messages.patients_count', ['count' => '941']) }}
                                <p class="text-grey">{{ __('messages.cohort_result', ['quarter' => 'Q1-2024']) }}</p>
                            </div>
                        </div>
                        <div class="cohort-quarter">
                            <div class="cohort-bar">
                                <div class="segment bp-controlled-bg" style="height: 80%">
                                    80%
                                </div>
                                <div class="segment bp-uncontrolled-bg" style="height: 12%">
                                    12%
                                </div>
                                <div class="segment three-month-ltfu-bg" style="height: 8%">
                                    8%
                                </div>
                            </div>
                            <div class="cohort-detail">
                                <b>{{ __('messages.cohort_name', ['quarter' => 'Q1-2024']) }}</b>
                                {{ __('messages.patients_count', ['count' => '1,281']) }}
                                <p class="text-grey">{{ __('messages.cohort_result', ['quarter' => 'Q2-2024']) }}</p>
                            </div>
                        </div>
                        <div class="cohort-quarter">
                            <div class="cohort-bar">
                                <div class="segment" style="height: 100%; border: 2px solid rgb(241, 241, 241)"></div>
                            </div>
                            <div class="cohort-detail">
                                <b>{{ __('messages.cohort_name', ['quarter' => 'Q2-2024']) }}</b>
                                {{ __('messages.patients_count', ['count' => '2,725']) }}
                                <p class="text-grey">{{ __('messages.coming_date', ['date' => 'Oct-2024']) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="key">
                        <div class="key-text">
                            <span class="key-color-box bp-controlled-bg"></span> {{ __('messages.key_bp_ctrl') }}
                        </div>
                        <div class="key-text">
                            <span class="key-color-box bp-uncontrolled-bg"></span> {{ __('messages.key_bp_unctrl') }}
                        </div>
                        <div class="key-text">
                            <span class="key-color-box three-month-ltfu-bg"></span>
                            {{ __('messages.key_no_visit_q') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="columns-header spacer">{{ __('messages.overdue_patients_header') }}</h2>
        <div class="columns-3">
            <div class="card normal-flow">
                <div class="heading">
                    <h3>{{ __('messages.overdue_patients') }}</h3>
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>{{ __('messages.numerator') }}:</span> {{ __('messages.overdue_num_desc') }}
                            </p>
                            <p>
                                <span>{{ __('messages.denominator') }}:</span>
                                {{ __('messages.overdue_denom_desc') }}
                            </p>
                            <p>
                                <span>{{ __('messages.overdue_def') }}</span> {{ __('messages.overdue_def_desc') }}
                            </p>
                        </div>
                    </div>
                    <p>{{ __('messages.overdue_card_text') }}</p>
                </div>
                <div class="body">
                    <div class="figures">
                        <div>
                            <p class="large-num overdue">26%</p>
                            <div class="detail">
                                <p>{{ __('messages.overdue_chart_text', ['count' => '2,800', 'date' => 'Jul-2024']) }}
                                </p>
                                <p class="text-grey">
                                    {{ __('messages.of') }}
                                    <span
                                        class="under-care">{{ __('messages.of_puc', ['count' => '10,800']) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="overdue"></canvas>
                    </div>
                </div>
            </div>

            <div class="card normal-flow">
                <div class="heading">
                    <h3>{{ __('messages.overdue_called') }}</h3>
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>{{ __('messages.numerator') }}:</span> {{ __('messages.overdue_called_num') }}
                            </p>
                            <p class="italic">
                                {{ __('messages.overdue_called_note') }}
                            </p>
                            <p>
                                <span>{{ __('messages.denominator') }}:</span>
                                {{ __('messages.overdue_called_denom') }}
                            </p>
                        </div>
                    </div>
                    <p>{{ __('messages.overdue_called_card_text') }}</p>
                </div>
                <div class="body">
                    <div class="figures">
                        <p class="large-num overdue-called">42%</p>
                        <div class="detail">
                            <p>{{ __('messages.overdue_called_chart', ['count' => '1,176', 'date' => 'Jul-2024']) }}
                            </p>
                            <p class="text-grey">
                                {{ __('messages.of') }}
                                <span class="overdue">{{ __('messages.of_overdue', ['count' => '2,800']) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="overdueCalled"></canvas>
                    </div>
                    <div class="extra">
                        <p><span class="agreed">47%</span>
                            {{ __('messages.agreed_to_visit', ['percent' => '47%', 'count' => '552']) }}</p>
                        <p><span class="remind">28%</span>
                            {{ __('messages.to_be_called_later', ['percent' => '28%', 'count' => '329']) }}</p>
                        <p><span class="removed">25%</span>
                            {{ __('messages.removed_from_list', ['percent' => '25%', 'count' => '294']) }}</p>
                    </div>
                </div>
            </div>

            <div class="card normal-flow">
                <div class="heading">
                    <h3>{{ __('messages.overdue_returned') }}</h3>
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>{{ __('messages.numerator') }}:</span> {{ __('messages.overdue_ret_num') }}
                            </p>
                            <p class="italic">
                                {{ __('messages.overdue_ret_note') }}
                            </p>
                            <p>
                                <span>{{ __('messages.denominator') }}:</span>
                                {{ __('messages.overdue_ret_denom') }}
                            </p>
                        </div>
                    </div>
                    <p>{{ __('messages.overdue_ret_card_text') }}</p>
                </div>
                <div class="body">
                    <div class="figures">
                        <div>
                            <p class="large-num overdue-returned">58%</p>
                            <div class="detail">
                                <p>{{ __('messages.overdue_ret_chart', ['count' => '682']) }}</p>
                                <p class="text-grey">
                                    {{ __('messages.of') }}
                                    <span
                                        class="overdue-called-denominator">{{ __('messages.of_called', ['count' => '1,176', 'date' => 'Jul-2024']) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="overdueReturned"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="columns-header spacer">4. Inventory indicators</h2>

        <div class="columns-3">
            <div class="card">
                <div class="heading">
                    <h3>Anti-hypertensive drug stock</h3>
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>Numerator:</span> Facilities with &ge;30 patient days
                                protocol drugs in stock.
                            </p>
                            <p>
                                <span>Denominator:</span> All facilities in the hypertension
                                program.
                            </p>
                        </div>
                    </div>
                    <p>
                        Facilities with &ge;30 patient days of hypertension protocol drugs
                        in stock
                    </p>
                </div>
                <div class="body">
                    <div class="key key-per-row">
                        <div class="key-text">
                            <span class="key-color-box step-one-drugs"></span><b>Step 1 drugs</b> — 94% in Jul-2024
                        </div>
                        <div class="key-text">
                            <span class="key-color-box step-two-drugs"></span><b>Step 2 drugs</b> — 64% in Jul-2024
                        </div>
                        <div class="key-text">
                            <span class="key-color-box step-three-drugs"></span><b>Step 3 drugs</b> — 90% in Jul-2024
                        </div>
                    </div>
                    <div>&nbsp;</div>
                    <div class="chart">
                        <canvas id="drugstock"></canvas>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="body">
                    <h3>Blood pressure monitors</h3>
                    <div class="info">
                        <div class="info-hover-text">
                            <p>
                                <span>Numerator:</span> Facilities with 1 or more functioning
                                BP monitors.
                            </p>
                            <p>
                                <span>Denominator:</span> All facilities in the hypertension
                                program.
                            </p>
                        </div>
                    </div>
                    <p>Facilities with a functioning BP monitor</p>
                    <div class="figures" style="margin-top: 2em">
                        <p class="large-num">98%</p>
                        <div class="detail">
                            <p>157 facilities with a functioning BP monitor</p>
                            <p class="text-grey">of 160 facilities reporting in Jul-2024</p>
                        </div>
                    </div>
                    <div class="text-grey facilities-without-bp-monitor" style="margin-top: 2em">
                        3 facilities without a functioning BP monitor:
                    </div>
                    <div class="text-grey facilities-without-bp-monitor">
                        <a href="#">CHC Turnip</a>, <a href="#">CHC Zucchiniville</a>,
                        <a href="#">PHC Grapefruit Hill</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="heading">
                <h3>{{ __('messages.stock_reporting') }}</h3>
                <p>{{ __('messages.reports', ['date' => 'Jul-2024']) }}</p>
                <div class="table-container">
                    <p class="table-scroll-message text-grey mobile-only">
                        {{ __('messages.scroll_table') }}
                    </p>
                    <div class="table-wrap">
                        <table id="table-stock-inventory">
                            <colgroup>
                                <col />
                                <col />
                                <col />
                                <col />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>
                                        <div>{{ __('messages.table_facility') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('messages.table_sub_region') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('messages.step_1_drugs') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('messages.step_2_drugs') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('messages.step_3_drugs') }}</div>
                                    </th>
                                    <th>
                                        <div>{{ __('messages.bp_monitors') }}</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($faskes) && $faskes->count() > 0)
                                    @foreach ($faskes as $fask)
                                        <tr>
                                            <th class="link"><a href="#">{{ $fask->nama_instansi }}</a></th>
                                            <td>{{ $fask->nama_kecamatan }}</td>
                                            <td class="number">-</td>
                                            <td class="number">-</td>
                                            <td class="number">-</td>
                                            <td class="number">-</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('messages.no_faskes_data') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="key">
                    <div class="key-text">
                        <span class="key-color-box expiry expiry-key"></span> &gt;180 days
                        drug supply
                    </div>
                    <div class="key-text">
                        <span class="key-color-box warning warning-key"></span> &lt;30
                        days drug supply or no BP monitor
                    </div>
                </div>
            </div>
        </div>

        <h2 class="columns-header spacer">4. Comorbidity indicators</h2>

        <div class="columns-3">
            <div class="card col-span-2" style="justify-content: start">
                <div class="heading">
                    <h3>Hypertension and diabetes comorbidity</h3>
                    <p>
                        All patients registered in the hypertension and/or diabetes
                        programs
                    </p>
                </div>

                <div class="table-container">
                    <p class="table-scroll-message text-grey mobile-only">
                        scroll table &rarr;
                    </p>
                    <div class="table-wrap">
                        <table>
                            <tr>
                                <th></th>
                                <th class="text-right">Patients under care</th>
                                <th class="text-right">Cumulative registered patients</th>
                                <th class="text-right">Died<sup>*</sup></th>
                            </tr>
                            <tr>
                                <th>Hypertension-only</th>
                                <td class="text-right">8,632</td>
                                <td class="text-right">10,000</td>
                                <td class="text-right">105</td>
                            </tr>
                            <tr>
                                <th>Both hypertension and diabetes</th>
                                <td class="text-right">2,000</td>
                                <td class="text-right">2,213</td>
                                <td class="text-right">100</td>
                            </tr>
                            <tr>
                                <th>Diabetes-only</th>
                                <td class="text-right">1,500</td>
                                <td class="text-right">2,100</td>
                                <td class="text-right">50</td>
                            </tr>
                            <tr class="totals">
                                <th>TOTAL</th>
                                <td class="text-right">12,132</td>
                                <td class="text-right">14,313</td>
                                <td class="text-right">255</td>
                            </tr>
                        </table>
                    </div>
                    <div class="key">
                        <div class="key-text">
                            <sup>*</sup>Deaths may be undercounted.
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <h3>Comorbid patients with BP controlled</h3>
                <p>
                    Patients with both hypertension and diabetes with BP controlled at
                    latest visit in past 3 months
                </p>
                <div class="figures">
                    <p class="large-num bp-controlled">50%</p>
                    <div class="detail">
                        <p>1,000 patients with BP &lt;140/90</p>
                    </div>
                </div>
                <div class="chart">
                    <canvas id="comodrbidcontrolled"></canvas>
                </div>
            </div>
        </div>

        <h2 class="columns-header spacer">5. Age and sex</h2>

        <div class="card">
            <div class="heading">
                <h3>Age and sex of patients under care for hypertension</h3>
                <p>
                    <span class="under-care">10,632 patients under care</span>
                </p>
            </div>
            <div class="body">
                <div class="table-container">
                    <p class="table-scroll-message text-grey mobile-only">
                        scroll table &rarr;
                    </p>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 10%; border-right: 0">Age</th>
                                    <th style="width: 40%" class="text-right">Female (64%)</th>
                                    <th style="width: 40%; border-right: 0">Male (36%)</th>
                                    <th style="width: 10%; border-left: 0">Female and male</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="border-right: 0">18-29</th>
                                    <td class="text-right">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: Thistle;
                          width: 24%;
                          display: inline-block;
                        ">
                                            6%
                                        </div>
                                    </td>
                                    <td style="border-right: 0">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: LightSteelBlue;
                          width: 16%;
                          display: inline-block;
                        ">
                                            4%
                                        </div>
                                    </td>
                                    <td style="border-left: 0" class="text-right">10%</td>
                                </tr>
                                <tr>
                                    <th style="border-right: 0">30-49</th>
                                    <td class="text-right">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: Thistle;
                          width: 100%;
                          display: inline-block;
                        ">
                                            28%
                                        </div>
                                    </td>
                                    <td style="border-right: 0">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: LightSteelBlue;
                          width: 68%;
                          display: inline-block;
                        ">
                                            17%
                                        </div>
                                    </td>
                                    <td style="border-left: 0" class="text-right">45%</td>
                                </tr>
                                <tr>
                                    <th style="border-right: 0">50-69</th>
                                    <td class="text-right">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: Thistle;
                          width: 76%;
                          display: inline-block;
                        ">
                                            19%
                                        </div>
                                    </td>
                                    <td style="border-right: 0">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: LightSteelBlue;
                          width: 24%;
                          display: inline-block;
                        ">
                                            6%
                                        </div>
                                    </td>
                                    <td style="border-left: 0" class="text-right">25%</td>
                                </tr>
                                <tr>
                                    <th style="border-right: 0">70+</th>
                                    <td class="text-right">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: Thistle;
                          width: 48%;
                          display: inline-block;
                        ">
                                            12%
                                        </div>
                                    </td>
                                    <td style="border-right: 0">
                                        <div
                                            style="
                          border-radius: 3px;
                          padding: 4px 8px;
                          color: rgba(0, 0, 0, 0.6);
                          background: LightSteelBlue;
                          width: 24%;
                          display: inline-block;
                        ">
                                            8%
                                        </div>
                                    </td>
                                    <td style="border-left: 0" class="text-right">20%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                const labels3m = @json($chartLabels3m);
                const labels12m = @json($chartLabels12m);
                
                // BP Controlled (3 months)
                const bpChart = Chart.getChart("bpcontrolled");
                if (bpChart && labels3m.length > 0) {
                    bpChart.data.labels = labels3m;
                    bpChart.data.datasets[0].data = @json($chartValues3m);
                    bpChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels3m.length);
                    bpChart.update();
                }

                // BP Uncontrolled (3 months)
                const bpUncontrolledChart = Chart.getChart("bpuncontrolled");
                if (bpUncontrolledChart && labels3m.length > 0) {
                    bpUncontrolledChart.data.labels = labels3m;
                    bpUncontrolledChart.data.datasets[0].data = @json($uncontrolledValues3m);
                    bpUncontrolledChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels3m.length);
                    bpUncontrolledChart.update();
                }

                // No visit in 3 months (3 months)
                const ltfu3mChart = Chart.getChart("ltfu3Month");
                if (ltfu3mChart && labels3m.length > 0) {
                    ltfu3mChart.data.labels = labels3m;
                    ltfu3mChart.data.datasets[0].data = @json($ltfu3mValues3m);
                    ltfu3mChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels3m.length);
                    ltfu3mChart.update();
                }

                // Patients Under Care (12 months trend)
                const regChart = Chart.getChart("registrations");
                if (regChart && labels12m.length > 0) {
                    regChart.data.labels = labels12m;
                    regChart.data.datasets[0].data = @json($cumRegValues12m);
                    regChart.data.datasets[1].data = @json($pucValues12m);
                    regChart.data.datasets[2].data = @json($monthlyNewValues12m);
                    regChart.update();
                }

                // 12 month LTFU (12 months trend)
                const ltfuChart = Chart.getChart("ltfu12Months");
                if (ltfuChart && labels12m.length > 0) {
                    ltfuChart.data.labels = labels12m;
                    ltfuChart.data.datasets[0].data = @json($ltfuValues12m);
                    ltfuChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels12m.length);
                    ltfuChart.update();
                }

                // Screening (12 months trend)
                const screenChart = Chart.getChart("screenings");
                if (screenChart && labels12m.length > 0) {
                    screenChart.data.labels = labels12m;
                    screenChart.data.datasets[0].data = @json($screenValues12m);
                    screenChart.data.datasets[1].data = @json($screenCountValues12m);
                    screenChart.data.datasets[0].segment.borderDash = (ctx) => dynamicChartSegementDashed(ctx, labels12m.length);
                    screenChart.update();
                }
            }, 500); 

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
                                            // Redirect to index with regency_id parameter when clicked
                                            html += `<li class="region-district">
                                              <span class="caret toggle-region" data-type="district" data-parent-id="${item.id}"></span>
                                              <a href="{{ route('hearts360.hipertensi') }}?regency_id=${item.id}">${item.name}</a>
                                              <ul class="nested"></ul>
                                          </li>`;
                                        } else if (type === 'district') {
                                            // Displaying districts, next is village
                                            // Redirect to index with district_id parameter when clicked
                                            html += `<li class="region-subdistrict">
                                              <span class="caret toggle-region" data-type="village" data-parent-id="${item.id}"></span>
                                              <a href="{{ route('hearts360.hipertensi') }}?district_id=${item.id}">${item.name}</a>
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
