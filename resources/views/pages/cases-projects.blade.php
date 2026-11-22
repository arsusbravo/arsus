@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/cases.js', 'bodyClass' => 'subpage log-page', 'current' => 'cases', 'page' => 'experiments'])

@section('title', __('shuttle.meta.experiments_title'))
@section('description', __('shuttle.meta.experiments_description'))

@section('before')
    <div class="rackbg" aria-hidden="true"></div>
@endsection

@php
    // filter chips: tags used by at least two experiments, most common first
    $tagCounts = [];
    foreach ($projects as $p) {
        foreach ($p['tags'] as $tag) {
            $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
        }
    }
    $tagCounts = array_filter($tagCounts, fn ($n) => $n >= 2);
    arsort($tagCounts);
@endphp

@section('content')
    <main>
        <section class="log-section">
            <a class="crumb" href="{{ locale_route('cases') }}">&larr; {{ __('shuttle.log.back') }}</a>
            <header class="log-head">
                <p class="eyebrow">{{ __('shuttle.log.exp_eyebrow') }}</p>
                <h1>{{ __('shuttle.log.exp_title') }} <span>{{ __('shuttle.log.exp_highlight') }}</span></h1>
                <p class="lead">{{ __('shuttle.log.exp_lead') }}</p>
            </header>

            <div class="chips" id="chips" role="group" aria-label="{{ __('shuttle.log.filter') }}">
                <button type="button" class="chip on" data-tag="*" aria-pressed="true">{{ __('shuttle.log.all') }}</button>
                @foreach (array_keys($tagCounts) as $tag)
                    <button type="button" class="chip" data-tag="{{ $tag }}" aria-pressed="false">{{ $tag }}</button>
                @endforeach
            </div>

            <div class="exp-grid" id="exp-grid">
                @foreach ($projects as $slug => $project)
                    @php $title = __("projects.items.{$slug}.title"); @endphp
                    <article class="exp-card screen-card" data-tags="{{ implode('|', $project['tags']) }}" style="--i:{{ $loop->index }}">
                        <div class="shot wide">
                            <img src="{{ asset($project['image']) }}" alt="{{ __('shuttle.log.screenshot_of', ['title' => $title]) }}" loading="lazy" />
                            <i class="led"></i>
                        </div>
                        <div class="card-body">
                            <div class="pills">
                                @foreach ($project['tags'] as $tag)<span class="pill">{{ $tag }}</span>@endforeach
                            </div>
                            <h2>{{ $title }}</h2>
                            <p>{{ __("projects.items.{$slug}.description") }}</p>
                            <a class="visit" href="{{ $project['url'] }}" target="_blank" rel="noopener noreferrer">{{ __('shuttle.log.visit') }} <span aria-hidden="true">&#8599;</span><span class="sr">{{ __('shuttle.log.new_tab') }}</span></a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="log-cta">
                <h2>{{ __('shuttle.log.exp_cta_title') }}</h2>
                <p>{{ __('shuttle.log.exp_cta_text') }}</p>
                <div class="hero-actions">
                    <a class="btn" href="{{ locale_route('contact') }}">{{ __('shuttle.open_comms') }} &rarr;</a>
                    <a class="btn ghost" href="{{ locale_route('home') }}#cases" data-return="cases">{{ __('shuttle.return_shuttle') }}</a>
                </div>
            </div>
        </section>
    </main>
@endsection
