@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/cases.js', 'bodyClass' => 'subpage log-page', 'current' => 'cases', 'page' => 'report'])

@section('title', $case['title'] . ' | Arsus')
@section('description', $case['subtitle'])

@section('before')
    <div class="rackbg" aria-hidden="true"></div>
@endsection

@section('content')
    <main>
        <section class="log-section">
            <a class="crumb" href="{{ locale_route('cases.success-stories') }}">&larr; {{ __('shuttle.log.all_missions') }}</a>
            <header class="log-head report-head">
                <p class="eyebrow">{{ __('shuttle.log.report') }} // {{ str_pad($index, 2, '0', STR_PAD_LEFT) }}</p>
                <h1>{{ $case['title'] }}</h1>
                <p class="sub">{{ $case['subtitle'] }}</p>
                <div class="pills">
                    @foreach ($case['tags'] as $tag)<span class="pill">{{ $tag }}</span>@endforeach
                </div>
            </header>
            <p class="lead report-lead">{{ $case['description'] }}</p>

            @if ($case['images'] > 1)
                <div class="gallery">
                    @for ($i = 1; $i < $case['images']; $i++)
                        @php $src = asset('images/cases/' . $case['case_number'] . '-' . $i . '.jpg'); @endphp
                        <button type="button" class="screen-card shot-btn" data-src="{{ $src }}" aria-label="{{ __('shuttle.log.enlarge', ['n' => $i]) }}">
                            <div class="shot"><img src="{{ $src }}" alt="{{ __('shuttle.log.shot_alt', ['title' => $case['title'], 'n' => $i]) }}" loading="lazy" /><i class="led"></i></div>
                        </button>
                    @endfor
                </div>
            @endif

            <div class="duo">
                <article class="panel warn"><h2><span class="ico" aria-hidden="true">!</span> {{ __('shuttle.log.challenge') }}</h2><p>{{ $case['challenge'] }}</p></article>
                <article class="panel ok"><h2><span class="ico" aria-hidden="true">&#10003;</span> {{ __('shuttle.log.solution') }}</h2><p>{{ $case['solution'] }}</p></article>
            </div>

            <section class="report-sec">
                <h2>{{ __('shuttle.log.features') }}</h2>
                <ul class="check">
                    @foreach ($case['features'] as $feature)<li>{{ $feature }}</li>@endforeach
                </ul>
            </section>

            <section class="report-sec">
                <h2>{{ __('shuttle.log.results') }}</h2>
                <ul class="stats-grid">
                    @foreach ($case['results'] as $result)
                        @if (preg_match('/^([\d.,]+(?:%|x)?)\s+(.*)$/iu', $result, $m))
                            <li class="stat"><b>{{ $m[1] }}</b><span>{{ $m[2] }}</span></li>
                        @else
                            <li class="stat text"><span>{{ $result }}</span></li>
                        @endif
                    @endforeach
                </ul>
            </section>

            <section class="report-sec">
                <h2>{{ __('shuttle.log.tech') }}</h2>
                <div class="pills big">
                    @foreach ($case['tech_stack'] as $tech)<span class="pill">{{ $tech }}</span>@endforeach
                </div>
            </section>

            <nav class="pager" aria-label="{{ __('shuttle.log.other') }}">
                <a href="{{ locale_route('case.show', ['slug' => $prev['slug']]) }}"><small>&larr; {{ __('shuttle.log.previous') }}</small>{{ $prev['title'] }}</a>
                <a href="{{ locale_route('case.show', ['slug' => $next['slug']]) }}" class="r"><small>{{ __('shuttle.log.next') }} &rarr;</small>{{ $next['title'] }}</a>
            </nav>

            <div class="log-cta">
                <h2>{{ __('shuttle.log.report_cta_title') }}</h2>
                <p>{{ __('shuttle.log.report_cta_text') }}</p>
                <div class="hero-actions">
                    <a class="btn" href="{{ locale_route('contact') }}">{{ __('shuttle.open_comms') }} &rarr;</a>
                    <a class="btn ghost" href="{{ locale_route('cases.success-stories') }}">{{ __('shuttle.log.all_missions') }}</a>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('after')
    <div class="lightbox" id="lightbox" hidden role="dialog" aria-modal="true" aria-label="{{ $case['title'] }}">
        <button class="lb-close" type="button" aria-label="{{ __('shuttle.log.close') }}">&times;</button>
        <img alt="" />
    </div>
@endsection
