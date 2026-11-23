@extends('layouts.shuttle', ['entry' => 'resources/js/shuttle/main.js', 'current' => 'home', 'homeNav' => true])

@section('title', __('shuttle.meta.home_title'))
@section('description', __('shuttle.meta.home_description'))

@push('head')
    {{-- coming back from a room page: cover the first frame while the camera flies back --}}
    <script>try{if(sessionStorage.getItem("arrive"))document.documentElement.classList.add("arriving")}catch(e){}</script>
@endpush

@section('before')
    <canvas id="scene"></canvas>
    <div class="boot-loader" id="boot-loader" aria-hidden="true">
        <div class="boot-ring"></div>
        <p>{{ __('shuttle.boot.label') }}</p>
    </div>
    <script>setTimeout(function () { var l = document.getElementById('boot-loader'); if (l) l.classList.add('hide'); }, 8000);</script>

    {{-- Cockpit minigame: hover-only hint on the steering yoke (shown/hidden purely via CSS
         opacity, toggled from main.js — not a permanent label like the door signs). --}}
    <div class="cockpit-hint" id="cockpit-hint" aria-hidden="true">{{ __('shuttle.game.hint') }}</div>

    {{-- Cockpit minigame: crosshair that follows the cursor while shooting, replacing the native
         pointer (see the 'playing'-phase branch in the pointermove handler in main.js). --}}
    <div class="game-crosshair" id="game-crosshair" hidden aria-hidden="true">
        <span></span><span></span><span></span><span></span>
    </div>

    {{-- Cockpit minigame: full-screen red strobe overlay, toggled in sync with the in-scene
         emergency light during the "asteroid attack" alert. --}}
    <div class="emergency-flash" id="emergency-flash" aria-hidden="true"></div>

    {{-- Cockpit minigame HUD: hidden skeleton, rendered server-side so copy is present in the
         HTML (no FOUC, and covered by the raw-translation-key regression test). main.js only
         toggles [hidden] and writes plain numbers via textContent. --}}
    <div class="cockpit-hud" id="cockpit-game" hidden>
        <div class="hud-alert" id="game-alert" role="alert">
            <strong>{{ __('shuttle.game.alert_title') }}</strong>
            <span>{{ __('shuttle.game.alert_sub') }}</span>
        </div>

        <div class="hud-stats" id="game-stats" hidden>
            <div class="stat hud-tile">
                <b id="game-score">0</b>
                <span>{{ __('shuttle.game.score_label') }}</span>
            </div>
            <div class="stat hud-tile">
                <b id="game-time">0</b>
                <span>{{ __('shuttle.game.time_label') }}</span>
            </div>
        </div>

        <div class="hud-complete" id="game-complete" hidden>
            <p class="status"><i></i>{{ __('shuttle.game.round_complete_title') }}</p>
            <p class="hud-complete-text">{{ __('shuttle.game.round_complete_text') }}</p>
            <div class="stat hud-tile">
                <b id="game-complete-score">0</b>
                <span>{{ __('shuttle.game.destroyed_label') }}</span>
            </div>
        </div>

        <button type="button" class="btn ghost hud-exit" id="game-exit">{{ __('shuttle.game.exit') }}</button>
    </div>
@endsection

@section('content')
    <main>
        <section class="hero">
            <h1>{{ __('shuttle.home.hero_title') }} <span>{{ __('shuttle.home.hero_highlight') }}</span></h1>
            <p>{{ __('shuttle.home.hero_text') }}</p>
        </section>

        {{-- A dedicated stop right outside door 01, closed, before stepping inside (see the
             camera keyframes in main.js) — no text here on purpose: the door's own "Meet the
             crew" sign is already clickable from this distance. --}}
        <section class="door-approach" aria-hidden="true"></section>

        @foreach ([
            ['id' => 'about', 'room' => 'crew', 'route' => 'about'],
            ['id' => 'services', 'room' => 'engine', 'route' => 'services'],
            ['id' => 'cases', 'room' => 'missions', 'route' => 'cases'],
            ['id' => 'contact', 'room' => 'comms', 'route' => 'contact'],
        ] as $s)
            <section id="{{ $s['id'] }}">
                <p class="eyebrow">{{ __('shuttle.rooms.' . $s['room']) }}</p>
                <h2>{{ __('shuttle.home.' . $s['room'] . '_title') }}</h2>
                <p>{{ __('shuttle.home.' . $s['room'] . '_text') }}</p>
                <a class="btn" href="{{ locale_route($s['route']) }}" data-zoom="{{ $s['route'] }}">{{ __('shuttle.home.' . $s['room'] . '_button') }} &rarr;</a>
            </section>
        @endforeach
    </main>
@endsection
