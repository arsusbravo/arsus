@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/cases.js', 'bodyClass' => 'subpage log-page', 'current' => 'cases', 'page' => 'hub'])

@section('title', __('shuttle.meta.cases_title'))
@section('description', __('shuttle.meta.cases_description'))

@section('before')
    <div class="rackbg" aria-hidden="true"></div>
@endsection

@section('content')
    <main>
        <section class="log-hero">
            <p class="eyebrow">{{ __('shuttle.log.hub_eyebrow') }}</p>
            <h1>{{ __('shuttle.log.hub_title') }} <span>{{ __('shuttle.log.hub_highlight') }}</span></h1>
            <p class="lead">{{ __('shuttle.log.hub_lead') }}</p>
        </section>

        <section class="portals">
            <a class="portal" href="{{ locale_route('cases.success-stories') }}">
                <div class="mosaic" aria-hidden="true">
                    @foreach (array_slice($cases, 0, 4) as $case)
                        <span class="tile"><img src="{{ asset($case['thumb']) }}" alt="" loading="lazy" /></span>
                    @endforeach
                </div>
                <div class="portal-body">
                    <p class="mod-id">{{ __('shuttle.log.file') }} 01 <i></i></p>
                    <h2>{{ __('shuttle.log.accomplished') }}</h2>
                    <p>{{ __('shuttle.log.accomplished_text') }}</p>
                    <p class="count">{{ __('shuttle.log.accomplished_count', ['count' => count($cases)]) }}</p>
                    <span class="btn">{{ __('shuttle.log.accomplished_open') }} &rarr;</span>
                </div>
            </a>
            <a class="portal" href="{{ locale_route('cases.projects') }}">
                <div class="mosaic" aria-hidden="true">
                    @foreach (array_slice($projects, 0, 4) as $project)
                        <span class="tile"><img src="{{ asset($project['image']) }}" alt="" loading="lazy" /></span>
                    @endforeach
                </div>
                <div class="portal-body">
                    <p class="mod-id">{{ __('shuttle.log.file') }} 02 <i class="amber"></i></p>
                    <h2>{{ __('shuttle.log.experiments') }}</h2>
                    <p>{{ __('shuttle.log.experiments_text') }}</p>
                    <p class="count">{{ __('shuttle.log.experiments_count', ['count' => count($projects)]) }}</p>
                    <span class="btn">{{ __('shuttle.log.experiments_open') }} &rarr;</span>
                </div>
            </a>
        </section>

        <section class="log-foot">
            <a class="btn ghost" href="{{ locale_route('home') }}#cases" data-return="cases">{{ __('shuttle.return_shuttle') }}</a>
        </section>
    </main>
@endsection
