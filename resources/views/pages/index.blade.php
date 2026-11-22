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
@endsection

@section('content')
    <main>
        <section class="hero">
            <h1>{{ __('shuttle.home.hero_title') }} <span>{{ __('shuttle.home.hero_highlight') }}</span></h1>
            <p>{{ __('shuttle.home.hero_text') }}</p>
        </section>

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
