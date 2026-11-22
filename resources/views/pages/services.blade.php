@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/services.js', 'bodyClass' => 'subpage svc-page', 'current' => 'services'])

@section('title', __('shuttle.meta.services_title'))
@section('description', __('shuttle.meta.services_description'))

@section('before')
    <canvas id="engine" aria-hidden="true"></canvas>
@endsection

@php
    $back = locale_route('home') . '#services';
    $icons = [
        'websites' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/><circle cx="6" cy="6.5" r=".6"/><circle cx="8.5" cy="6.5" r=".6"/>',
        'webapps' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 4v16M9 12h12"/>',
        'webshops' => '<circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h3l2.6 12.2a1 1 0 0 0 1 .8H18a1 1 0 0 0 1-.8L20.5 8H6"/>',
        'saas' => '<path d="M7 18a4 4 0 0 1-.6-7.96A6 6 0 0 1 18 9.5a4.2 4.2 0 0 1-.5 8.5H7z"/>',
        'crm' => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20a6 6 0 0 1 12 0"/><circle cx="17" cy="9" r="2.5"/><path d="M16.5 14.2A5 5 0 0 1 21 19.5"/>',
        'erp' => '<rect x="9" y="3" width="6" height="5" rx="1"/><rect x="3" y="16" width="6" height="5" rx="1"/><rect x="15" y="16" width="6" height="5" rx="1"/><path d="M12 8v4M6 16v-4h12v4"/>',
        'cms' => '<path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4M9 12h6M9 16h6"/>',
        'mobile' => '<rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M11 18.5h2"/>',
    ];
    $levels = ['maintenance' => .3, 'custom' => .65, 'new' => 1];
@endphp

@section('content')
    <main>
        <section class="svc-hero">
            <div class="svc-hero-text">
                <p class="eyebrow">{{ __('shuttle.services.eyebrow') }}</p>
                <h1>{{ __('shuttle.services.title') }} <span>{{ __('shuttle.services.title_highlight') }}</span></h1>
                <p class="lead">{{ __('shuttle.services.lead') }}</p>
                <div class="hero-actions">
                    <a class="btn" href="#capabilities">{{ __('shuttle.services.explore') }} &darr;</a>
                    <a class="btn ghost" href="{{ $back }}" data-return="services">{{ __('shuttle.return_shuttle') }}</a>
                </div>
            </div>
            <div class="rail" aria-label="{{ __('shuttle.services.stack_aria') }}">
                <p class="rail-label"><i></i> {{ __('shuttle.services.stack_label') }}</p>
                <div class="rail-window"><ul class="rail-track" id="rail"></ul></div>
            </div>
        </section>

        <section id="capabilities" class="svc-section">
            <header class="svc-head">
                <p class="eyebrow">{{ __('shuttle.services.modules_eyebrow') }}</p>
                <h2>{{ __('shuttle.services.modules_title') }}</h2>
                <p>{{ __('shuttle.services.modules_text') }}</p>
            </header>
            <div class="modules">
                @foreach (__('shuttle.services.modules') as $key => $module)
                    <article class="module" style="--i:{{ $loop->index }}">
                        <span class="mod-id">MOD-{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} <i></i></span>
                        <svg viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$key] !!}</svg>
                        <h3>{{ $module['title'] }}</h3>
                        <p>{{ $module['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="modes" class="svc-section">
            <header class="svc-head">
                <p class="eyebrow">{{ __('shuttle.services.modes_eyebrow') }}</p>
                <h2>{{ __('shuttle.services.modes_title') }}</h2>
                <p>{{ __('shuttle.services.modes_text') }}</p>
            </header>
            <div class="modes">
                @foreach (__('shuttle.services.modes') as $key => $mode)
                    <article class="mode" style="--lvl:{{ $levels[$key] }}">
                        <div class="throttle" aria-hidden="true"><span></span></div>
                        <div>
                            <p class="mode-tag">{{ $mode['tag'] }}</p>
                            <h3>{{ $mode['title'] }}</h3>
                            <p>{{ $mode['text'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="svc-cta">
            <h2>{{ __('shuttle.services.cta_title') }}</h2>
            <p>{{ __('shuttle.services.cta_text') }}</p>
            <div class="hero-actions">
                <a class="btn" href="{{ locale_route('contact') }}">{{ __('shuttle.open_comms') }} &rarr;</a>
                <a class="btn ghost" href="{{ $back }}" data-return="services">{{ __('shuttle.return_shuttle') }}</a>
            </div>
        </section>
    </main>
@endsection
