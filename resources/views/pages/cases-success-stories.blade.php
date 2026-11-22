@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/cases.js', 'bodyClass' => 'subpage log-page', 'current' => 'cases', 'page' => 'accomplished'])

@section('title', __('shuttle.meta.accomplished_title'))
@section('description', __('shuttle.meta.accomplished_description'))

@section('before')
    <div class="rackbg" aria-hidden="true"></div>
@endsection

@section('content')
    <main>
        <section class="log-section">
            <a class="crumb" href="{{ locale_route('cases') }}">&larr; {{ __('shuttle.log.back') }}</a>
            <header class="log-head">
                <p class="eyebrow">{{ __('shuttle.log.acc_eyebrow') }}</p>
                <h1>{{ __('shuttle.log.acc_title') }} <span>{{ __('shuttle.log.acc_highlight') }}</span></h1>
                <p class="lead">{{ __('shuttle.log.acc_lead') }}</p>
            </header>
            <div class="case-grid">
                @foreach ($cases as $case)
                    <a class="case-card screen-card" href="{{ locale_route('case.show', ['slug' => $case['slug']]) }}" style="--i:{{ $loop->index }}">
                        <div class="shot">
                            <img src="{{ asset($case['thumb']) }}" alt="" loading="lazy" />
                            <i class="led"></i>
                            <span class="mid">{{ __('shuttle.log.mission') }} {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="card-body">
                            <div class="pills">
                                @foreach ($case['tags'] as $tag)<span class="pill">{{ $tag }}</span>@endforeach
                            </div>
                            <h2>{{ $case['title'] }}</h2>
                            <p>{{ $case['summary'] }}</p>
                            <span class="visit">{{ __('shuttle.log.open_report') }} <span aria-hidden="true">&rarr;</span></span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="log-foot">
                <a class="btn ghost" href="{{ locale_route('home') }}#cases" data-return="cases">{{ __('shuttle.return_shuttle') }}</a>
            </div>
        </section>
    </main>
@endsection
