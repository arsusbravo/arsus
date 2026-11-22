{{--
    Layout for the 3D shuttle site.
    Pass via @extends('layouts.shuttle', [...]):
      entry     - page script under resources/js/shuttle/ (required)
      bodyClass - extra body classes (e.g. 'subpage svc-page')
      current   - active nav item: home | about | services | cases | contact
      homeNav   - true on the home page: nav links scroll to the rooms instead of leaving the page
      noNav     - true on the full-screen monitor pages (crew file, comms)
      page      - data-page value used by cases.js
--}}
@php
    $locale = app()->getLocale();
    // same path in another locale (mirrors the language switcher logic)
    $plainPath = trim(preg_replace('#^(nl|id)(/|$)#', '', request()->path()), '/');
    $altUrl = fn ($l) => url(($l === 'en' ? '' : $l) . ($plainPath !== '' ? ($l === 'en' ? '' : '/') . $plainPath : ''));
    $routes = [
        'home' => locale_route('home'),
        'about' => locale_route('about'),
        'services' => locale_route('services'),
        'cases' => locale_route('cases'),
        'success' => locale_route('cases.success-stories'),
        'projects' => locale_route('cases.projects'),
        'contact' => locale_route('contact'),
        'contactSubmit' => locale_route('contact.submit'),
    ];
    $navItems = [
        ['key' => 'crew', 'route' => 'about', 'anchor' => 'about'],
        ['key' => 'engine', 'route' => 'services', 'anchor' => 'services'],
        ['key' => 'missions', 'route' => 'cases', 'anchor' => 'cases'],
        ['key' => 'comms', 'route' => 'contact', 'anchor' => 'contact'],
    ];
    $config = [
        'locale' => $locale,
        'routes' => $routes,
        't' => [
            'rooms' => __('shuttle.rooms'),
            'signs' => __('shuttle.signs'),
            'sound' => __('shuttle.sound'),
            'nav' => __('shuttle.nav'),
            'comms' => __('shuttle.comms'),
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('shuttle.meta.home_title'))</title>
    <meta name="description" content="@yield('description', __('shuttle.meta.home_description'))">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ $altUrl($locale) }}">
    @foreach (['en', 'nl', 'id'] as $l)
        <link rel="alternate" hreflang="{{ $l }}" href="{{ $altUrl($l) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $altUrl('en') }}">
    <link rel="icon" href="{{ asset('images/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700&family=Orbitron:wght@500;600;700;800&display=swap" rel="stylesheet">
    @stack('head')
    @vite(['resources/css/shuttle.css', $entry])
</head>
<body class="{{ $bodyClass ?? '' }}" @isset($page) data-page="{{ $page }}" @endisset>
    @yield('before')

    @unless ($noNav ?? false)
        <header class="nav">
            <a class="logo" href="{{ $routes['home'] }}" aria-label="arsus"><span class="br">&lt;</span><span class="name">arsus</span><span class="br sl">/&gt;</span></a>
            <nav aria-label="{{ __('shuttle.nav.main') }}">
                @foreach ($navItems as $i => $item)
                    <a href="{{ ($homeNav ?? false) ? '#' . $item['anchor'] : $routes[$item['route']] }}"
                       data-num="{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}"
                       data-room="{{ __('shuttle.rooms.' . $item['key']) }}"
                       @if (($current ?? null) === $item['route']) aria-current="page" @endif>{{ __('shuttle.nav.' . $item['key']) }}</a>
                @endforeach
            </nav>
            <div class="lang-switch" role="group" aria-label="{{ __('shuttle.nav.language') }}">
                @foreach (config('app.locale_names') as $l => $name)
                    <a href="{{ $altUrl($l) }}" hreflang="{{ $l }}" lang="{{ $l }}" title="{{ $name }}"
                       @if ($l === $locale) aria-current="true" @endif>{{ strtoupper($l) }}</a>
                @endforeach
            </div>
        </header>
    @endunless

    @yield('content')

    <button id="sound-toggle" class="sound-toggle" type="button" aria-pressed="false" aria-label="{{ __('shuttle.sound.turn_on') }}">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path class="spk" d="M4 9h4l5-4v14l-5-4H4z"/><path class="wave" d="M16.5 8.5a5 5 0 0 1 0 7M19 6a8.5 8.5 0 0 1 0 12"/><path class="mute" d="M16 9l5 6M21 9l-5 6"/></svg>
        <span class="eq" aria-hidden="true"><i></i><i></i><i></i></span>
        <span class="lbl">{{ __('shuttle.sound.label') }}</span>
    </button>

    @yield('after')

    <script type="application/json" id="shuttle-config">@json($config)</script>
</body>
</html>
